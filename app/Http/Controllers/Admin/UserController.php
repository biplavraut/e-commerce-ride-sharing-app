<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\Api\Ride\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use App\Custom\Sms\AakashSms;

class UserController extends CommonController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->website['routeType'] = 'user';
        $this->userService          = $userService;
    }

    public function index()
    {
        return UserResource::collection($this->userService->getForIndex($this->paginationLimit));
        // $this->website['models'] = $this->userService->getPaginatedList($this->paginationLimit);

        // return view('admin.user.index', $this->website);
    }

    public function changepassword(Request $request){
        $getUser = User::findOrFail($request->id);
        $newpassword = Str::random(8);
        $encPassword = bcrypt($newpassword);
        $getUser->password = $encPassword;
        $getUser->save();
        $message = "Hello ".ucfirst($getUser->first_name);
        $message .= ", Your new login password is: $newpassword";
        $message .= "\nTeam gogo20";
        $sms = new AakashSms('cff2ae1a41a646143b6f90832ed0482c6918e85c1fe9025deb14c8811f0cf824', $getUser->phone, $message);
        $response = $sms->sendMessage();
    }

    public function store(UserRequest $request)
    {
        return $this->userService->store()
            ? redirect()->route('user.index')->with('success', 'User successfully created')
            : back()->with('failure', 'Sorry, user could not be created. Please try again later');
    }

    public function update(UserRequest $request, $id)
    {
        return $this->userService->update($id)
            ? back()->with('success', 'User successfully updated')
            : back()->with('failure', 'Sorry, User could not be updated. Please try again later');
    }

    public function destroy($id)
    {
        if (auth()->id() == $id || $id == 1 || auth()->id() != 1) {
            return back()->with('failure', 'Access denied.');
        }
        try {
            $this->userService->delete($id);

            return back()->with('success', 'User successfully deleted.');
        } catch (\Exception $exception) {
            return back()->with('failure', 'User could not be deleted. Please try again later.');
        }
    }

    public function search(Request $request)
    {
        return UserResource::collection($this->userService->query()->where('first_name', 'LIKE', '%' . $request->name . '%')->orWhere('id', $request->name)->orWhere('refer_code', $request->name)->orWhere('phone', $request->name)->take(10)->get());
    }
}
