<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\EliteUserRequest;
use App\Custom\Sms\Sparrow;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Custom\Sms\AakashSms;
use App\Services\UserService;
use App\Custom\PushNotification;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\Api\Ride\UserResource;

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
    }

    public function changepassword(Request $request)
    {
        $getUser = User::findOrFail($request->id);
        $newpassword = Str::random(8);
        $encPassword = bcrypt($newpassword);
        $getUser->password = $encPassword;
        $getUser->save();
        $message = "Hello " . ucfirst($getUser->first_name) . ",\n";
        $message .= "Your new login password is: $newpassword";
        $message .= "\nTeam gogo20";
        $sms = new Sparrow($getUser->phone, $message);
        $response = $sms->sendMessage();

        if ($getUser->access_token) {
            JWTAuth::setToken($getUser->access_token)->invalidate();
        }
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
        if (Str::contains($request->name, 'GGU')) {
            $id = (int)substr($request->name, 11);
            $users =  UserResource::collection($this->userService
                ->query()->where('id', $id)->paginate($this->paginationLimit));
        } else {
            $users =  UserResource::collection($this->userService
                ->query()
                ->orWhereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ")
                // ->where('first_name', 'LIKE', $request->name . '%')
                // ->orWhere('last_name', 'LIKE', $request->name . '%')
                ->orWhere('phone', 'LIKE', "%$request->name%")
                ->orWhere('refer_code', $request->name)
                ->orWhere('used_code', $request->name)
                ->paginate($this->paginationLimit));
        }


        return $users;
    }

    public function handleTabState(Request $request)
    {
        $users = null;
        if ($request->block == 1) {
            $users = $this->userService->query()->where('blocked', 1)->paginate($this->paginationLimit);
            $users->appends(['block' => 1])->links();
            $newPath = $users->path() . '?block=1';
        }

        if ($request->active == 1) {
            $users = $this->userService->query()->where('last_login_at', '>=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))->orderBy('last_login_at', 'desc')->paginate($this->paginationLimit);
            $users->appends(['active' => 1])->links();
            $newPath = $users->path() . '?active=1';
        }

        if ($request->passive == 1) {
            $users = $this->userService->query()->where('last_login_at', '<=', date('Y-m-d H:i:s', strtotime(now() . ' -30 days')))->orWhere('last_login_at', null)->orderBy('last_login_at', 'desc')->paginate($this->paginationLimit);
            $users->appends(['passive' => 1])->links();
            $newPath = $users->path() . '?passive=1';
        }

        if ($request->top == 1) {
            $users = $this->userService->query()->where('reward_point', '>', 0)->orderBy('reward_point', 'desc')->take(50)->get();
            $users->appends(['top' => 1])->links();
            $newPath = $users->path() . '?top=1';
        }

        if ($request->elite == 1) {
            $users = $this->userService->query()->where('elite', 1)->latest()->paginate($this->paginationLimit);
            $users->appends(['elite' => 1])->links();
            $newPath = $users->path() . '?elite=1';
        }
        return UserResource::collection($users)->additional(['meta' => [
            'newPath' => $newPath,
        ]]);;
    }

    public function blockState(Request $request)
    {
        $user = $this->userService->findOrFail($request->id);
        $message = "Operation success.";

        if ($request->state == "true") {
            $user->update(['blocked' => 0]);
            $message = "User has been unblocked.";
        } else {
            $user->update(['blocked' => 1]);
            if ($user->access_token) {
                JWTAuth::setToken($user->access_token)->invalidate();
            }
            $message = "User has been blocked.";
        }
        return response($message);
    }

    public function eliteRequestList()
    {
        $requests = EliteUserRequest::pluck('user_id');
        return UserResource::collection($this->userService->query()->whereIn('id', $requests)->latest()->paginate($this->paginationLimit));
    }

    public function eliteRequestDelete(Request $request)
    {
        $requests = EliteUserRequest::where('user_id', $request->user)->first();
        if ($requests) {
            $requests->delete();
            return response('success');
        }

        return response('error');
    }

    public function eliteState(Request $request)
    {
        $requests = EliteUserRequest::where('user_id', $request->user)->first();
        if ($requests) {
            $user = User::findOrFail($request->user);
            if ($request->state == "yes") {
                $user->update(['elite' => 1]);
                try {
                    $notification = new PushNotification(
                        $user->devices->pluck('device_token')->toArray(),
                        [
                            'title' => 'Elite User',
                            'message' =>  'You are promoted as gogoElite.',
                            'type' => 'elite',
                        ]
                    );
                    $notification->send();
                    $user->myNotifications()->create(['title' => 'Elite User', 'message' => 'You are promoted as gogoElite.', 'type' => 'elite', 'task' => 'elite']);
                } catch (\Throwable $th) {
                    //throw $th;
                }
            } else {
                $user->update(['elite' => 0]);
            }
            $requests->delete();
            return response('success');
        }

        return response('error');
    }

    public function customNotification(Request $request)
    {
        $user = User::findOrFail($request->user);
        $notification = new PushNotification(
            $user->devices->pluck('device_token')->toArray(),
            [
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
            ]
        );
        $notification->send();
        $user->myNotifications()->create(['title' => $request->title, 'message' => $request->message, 'type' => $request->type]);
        return response('success');
    }
}
