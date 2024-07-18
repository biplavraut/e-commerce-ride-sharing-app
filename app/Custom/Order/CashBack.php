<?php

namespace App\Custom\Order;

use App\Custom\PushNotification;
use App\DefaultConf;
use App\User;

class CashBack
{
    public function __construct($purchaseTotal)
    {
        $this->purchaseTotal = $purchaseTotal;
    }
    public function calculateCashback()
    {
        $defaultConf = DefaultConf::firstOrFail();

        if ($defaultConf->cashback_percent > 0 && $defaultConf->purchase_total > 0) {
            if ($this->purchaseTotal >= $defaultConf->purchase_total) {
                $cashback = round(($this->purchaseTotal * $defaultConf->cashback_percent) / 100);
                return $cashback;
            }
        }
        return 0;
    }

    public function processCashback($userId, $cashback)
    {
        # code...
        $user = User::query()->where('id', $userId)->first();
        if ($user) {
            if ($user->update(['reward_point' => $user->reward_point + $cashback])) {
                $user->transactionHistories()->create(['payment_mode' => 'gogoReward', 'point' => $cashback, 'from' => 'order cashback']);
                $notification = new PushNotification(
                    $user->devices->pluck('device_token')->toArray(),
                    [
                        'title' => 'gogoReward received',
                        'message' => 'You have received ' . $cashback . ' reward from order cashback.',
                        'type' => 'order-cashback',
                    ]
                );
                $notification->send();
                $user->myNotifications()->create(['title' => 'Order cashback', 'message' => 'You have received ' . $cashback . ' reward from order cashback.', 'type' => 'order-cashback']);
                return true;
            } else {
                return "Unable to update cashback.";
            }
        } else {
            return "User not found.";
        }
    }
}
