<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\MyWallet;
use App\UserDevice;
use App\UserNotification;
use App\WalletAdvanceLogs;
use App\TransactionHistory;
use Illuminate\Http\Request;
use App\Custom\PushNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\WalletLogResource;
use App\Http\Resources\Admin\WalletPaymentLogResource;
use App\Services\UserService;
use App\WalletPaymentLog;

class WalletController extends CommonController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();
        $this->userService          = $userService;
    }
    public function list()
    {
        return WalletLogResource::collection(WalletAdvanceLogs::latest()->paginate(20));
    }

    public function addPoint(Request $request)
    {
        $admin = auth()->user();
        $request->merge(['remarks' => $request->remarks . ' (By: ' . $admin->id . '. ' . $admin->name . ')']);
        DB::beginTransaction();
        try {
            $user = MyWallet::where('user_id', $request->user_id)->first();
            $log = WalletAdvanceLogs::create($request->all());
            if ($log) {
                if ($user) {
                    $amount = $user->amount + $request->amount;
                    $user->update(['amount' => $amount]);
                    UserNotification::create(['user_id' => $request->user_id, 'title' => 'Valued User', 'message' => $request->amount . ' added to your gogoPoint.', 'type' => 'credit', 'task' => 'load point']);
                    TransactionHistory::create(['user_id' => $request->user_id, 'payment_mode' => 'gogo20', 'point' => $request->amount, 'from' => 'gogoPoint Credit']);
                    $this->sendNotification($request->user_id);
                } else {
                    MyWallet::create(['user_id' => $request->user_id, 'amount' => $request->amount]);
                    UserNotification::create(['user_id' => $request->user_id, 'title' => 'Valued User', 'message' => $request->amount . ' added to your gogoPoint.', 'type' => 'credit', 'task' => 'load point']);
                    TransactionHistory::create(['user_id' => $request->user_id, 'payment_mode' => 'gogo20', 'point' => $request->amount, 'from' => 'gogoPoint Credit']);
                    $this->sendNotification($request->user_id);
                }
            }
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Wallet Point Added Successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    public function sendNotification($userId)
    {
        $tokens = UserDevice::where('user_id', $userId)->pluck('device_token')->toArray();
        $notification = new PushNotification(
            $tokens,
            [
                'title' => 'gogoPoint Added',
                'message' => 'Dear gogoUser, As you are our valued user you got credit from gogo20 which can be used for any services.',
                'type' => 'gogoPoint',
            ]
        );
        $notification->send();
    }

    public function search(Request $request)
    {
        $users =  $this->userService->query()
            ->orWhereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ")
            // ->where('first_name', 'LIKE', $request->name . '%')
            // ->orWhere('last_name', 'LIKE', $request->name . '%')
            ->orWhere('phone', 'LIKE', "%$request->name%")
            ->pluck('id');
        return WalletLogResource::collection(WalletAdvanceLogs::query()->whereIn('user_id', $users)->latest()->paginate(20));
    }

    public function walletPaymentList()
    {
        return WalletPaymentLogResource::collection(WalletPaymentLog::latest()->paginate(20));
    }

    public function walletPaymentSearch(Request $request)
    {
        $users =  $this->userService->query()
            ->orWhereRaw("concat(first_name, ' ', last_name) like '%$request->name%' ")
            // ->where('first_name', 'LIKE', $request->name . '%')
            // ->orWhere('last_name', 'LIKE', $request->name . '%')
            ->orWhere('phone', 'LIKE', "%$request->name%")
            ->pluck('id');
        return WalletPaymentLogResource::collection(WalletPaymentLog::query()->whereIn('user_id', $users)->orWhere('payment_mode', 'LIKE', "%$request->name%")->latest()->paginate(20));
    }
}
