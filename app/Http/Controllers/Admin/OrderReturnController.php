<?php

namespace App\Http\Controllers\Admin;

use App\Custom\PushNotification;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderReturnResource;
use App\MyWallet;
use App\Services\OrderReturnService;
use App\TransactionHistory;
use App\UserDevice;
use App\UserNotification;
use App\VendorDevice;
use App\WalletAdvanceLogs;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderReturnController extends CommonController
{
    /** @var OrderReturnService */
    private $orderReturnService;

    public function __construct(OrderReturnService $orderReturnService)
    {
        parent::__construct();
        $this->orderReturnService = $orderReturnService;
    }
    public function index()
    {
        $returns = $this->orderReturnService->query()->where('status', '!=', 'resolved')->with('user', 'vendor', 'order', 'orderItem', 'product')->latest()->paginate(10);
        return OrderReturnResource::collection($returns);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeReturnStatus(Request $request)
    {
        try {
            $returns = $this->orderReturnService->query()->where('id', $request->order_return_id)->first();
            $updateReturns = $returns->update(['status' => $request->status]);
            if ($request->status === 'proceed-to-vendor') {
                $tokens = VendorDevice::where('vendor_id', $returns->vendor_id)->pluck('device_token')->toArray();
                $notification = new PushNotification(
                    $tokens,
                    [
                        'title' => 'Return Request',
                        'message' => 'Dear gogoVendor, A retun request has been places by user from gogo20.',
                        'type' => 'return',
                    ]
                );
                $notification->send();
            }
            return response()->json(['status' => true, 'message' => 'Return Status Changed.']);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function resolveReturn(Request $request)
    {
        # code...
        if ($request->wallet) {
            DB::beginTransaction();
            try {
                $user = MyWallet::where('user_id', $request->user_id)->first();
                $log = WalletAdvanceLogs::create(['user_id' => $request->user_id, 'amount' => $request->amount, 'remarks' => $request->remarks]);
                if ($log) {
                    if ($user) {
                        $amount = $user->amount + $request->amount;
                        $user->update(['amount' => $amount]);
                        UserNotification::create(['user_id' => $request->user_id, 'title' => 'Elite User', 'message' => $request->amount . ' added to your gogoPoint.', 'type' => 'return', 'task' => 'resolved']);
                        TransactionHistory::create(['user_id' => $request->user_id, 'payment_mode' => 'gogo20', 'point' => $request->amount, 'from' => 'Order Return']);
                        $this->sendNotification($request->user_id);
                    } else {
                        MyWallet::create(['user_id' => $request->user_id, 'amount' => $request->amount]);
                        UserNotification::create(['user_id' => $request->user_id, 'title' => 'Order Return', 'message' => $request->amount . ' added to your gogoPoint.', 'type' => 'return', 'task' => 'resolved']);
                        TransactionHistory::create(['user_id' => $request->user_id, 'payment_mode' => 'gogo20', 'point' => $request->amount, 'from' => 'Order Return']);
                        $this->sendNotification($request->user_id);
                    }
                    $returns = $this->orderReturnService->query()->where('id', $request->order_return_id)->first();
                    $update = $returns->update(['status' => 'resolved', 'remarks' => $request->remarks . '(' . auth()->guard('admin')->user()->name . ')' . "\r\n" . $returns->remarks]);
                }
                DB::commit();
                return response()->json(['status' => true, 'message' => 'Return Resolved Successfully.']);
            } catch (Exception $e) {
                DB::rollBack();
                return $e;
            }
        } else {
            $returns = $this->orderReturnService->query()->where('id', $request->order_return_id)->first();
            $update = $returns->update(['status' => 'resolved', 'remarks' => $request->remarks . '(' . auth()->guard('admin')->user()->name . ')' . "\r\n" . $returns->remarks]);
            $this->sendNotification($request->user_id);
            return response()->json(['status' => true, 'message' => 'Return Resolved Successfully.']);
        }
    }

    public function sendNotification($userId)
    {
        $tokens = UserDevice::where('user_id', $userId)->pluck('device_token')->toArray();
        $notification = new PushNotification(
            $tokens,
            [
                'title' => 'gogoReturn Resolved',
                'message' => 'Dear gogoUser, Your retun request has been resolved from gogo20.',
                'type' => 'return',
            ]
        );
        $notification->send();
    }
}
