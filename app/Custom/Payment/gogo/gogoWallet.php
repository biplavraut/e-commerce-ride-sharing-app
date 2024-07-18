<?php

namespace App\Custom\Payment\gogo;

use App\Custom\PushNotification;
use App\MyWallet;
use App\User;

class gogoWallet
{
    protected $user;
    protected $total;

    public function __construct(User $user, $total)
    {
        $this->user = $user;
        $this->total = $total;
    }

    public function operation(): bool
    {
        if ($this->total == 0) {
            return false;
        }
        $gogoCash = 0;
        try {
            $gogoCash = $this->user->gogoWallet->amount;
        } catch (\Throwable $th) {
            return false;
        }

        if ($gogoCash < $this->total) {
            return false;
        } else {
            $updatedWallet = $this->user->gogoWallet->update(['amount' => $gogoCash - $this->total]);
        }
        // if ($gogoCash >= $this->total) {
        //     $updatedWallet = $this->user->gogoWallet->update(['amount' => $gogoCash - $this->total]);
        // } else {
        //     $finalReward = $rewardPoint - ($this->total - $gogoCash);
        //     $this->user->update(['reward_point' => $finalReward]);
        //     $updatedWallet = $this->user->gogoWallet->update(['amount' => 0]);
        // }

        return true;
    }

    public function refundGogoWallet(): bool
    {

        $gogoCash = 0;
        if ($this->user->gogoWallet) {
            $gogoCash = $this->user->gogoWallet->amount;
            $this->user->gogoWallet->update(['amount' => $gogoCash + $this->total]);
        } else {
            MyWallet::create(['user_id' => $this->user->id, 'amount' => $this->total]);
        }
        $this->user->transactionHistories()->create(['payment_mode' => 'gogoWallet', 'point' => $this->total, 'type' => 'received', 'from' => 'Order Cancellation']);
        $notification = new PushNotification(
            $this->user->devices->pluck('device_token')->toArray(),
            [
                'title' => 'gogoPoint received',
                'message' => 'You have received ' . $this->total . ' reward from order cancellation.',
                'type' => 'order-cashback',
            ]
        );
        $notification->send();
        $this->user->myNotifications()->create(['title' => 'Order cancellation', 'message' => 'You have received ' . $this->total . ' reward from order cancellation.', 'type' => 'order-cancellation']);
        return true;
    }
}
