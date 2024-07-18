<?php

namespace App\Custom\Order;

use App\Custom\PushNotification;
use App\DefaultConf;
use App\UserDevice;

class RedeemReward
{
    public function __construct($purchaseTotal, $user)
    {
        $this->purchaseTotal = $purchaseTotal;
        $this->user = $user;
        $this->defaultConf = DefaultConf::firstOrFail();
    }
    public function calculateRedeem()
    {
        $myReward = $this->user->reward_point;
        $redeemPercent = $this->defaultConf->reward_redeem_limit;
        $redeemablePoint = round(($this->purchaseTotal * $redeemPercent) / 100);
        if ($myReward > $redeemablePoint) {
            // Update user reward
            $this->user->update(['reward_point' => $this->user->reward_point - $redeemablePoint]);
            return $redeemablePoint;
        } else {

            $this->user->update(['reward_point' => 0]);
            return $myReward;
        }
    }

    public function refundGogoReward($point)
    {
        $this->user->update(['reward_point' => $this->user->reward_point + $point]);
        $this->user->transactionHistories()->create(['payment_mode' => 'gogoReward', 'type' => "received", 'point' => $point, 'from' => 'gogoReward Refund']);
        $this->sendNotification($this->user->id, $point);

        return true;
    }

    public function refundAndCalculateRedeem($holdPoint)
    {
        $redeemPercent = $this->defaultConf->reward_redeem_limit;
        $redeemablePoint = round(($this->purchaseTotal * $redeemPercent) / 100);
        if ($this->user->reward_point + $holdPoint > $redeemablePoint) {
            // Update user reward
            $this->user->update(['reward_point' => $this->user->reward_point + $holdPoint - $redeemablePoint]);
            if ($holdPoint - $redeemablePoint > 0) {
                $this->user->transactionHistories()->create(['payment_mode' => 'gogoReward', 'type' => "received", 'point' => $holdPoint - $redeemablePoint, 'from' => 'gogoReward Refund']);
            }
            return $redeemablePoint;
        } else {
            $this->user->update(['reward_point' => $this->user->reward_point + $holdPoint]);
            return $this->user->reward_point;
        }
    }

    public function sendNotification($userId, $point)
    {
        $tokens = UserDevice::where('user_id', $userId)->pluck('device_token')->toArray();
        $notification = new PushNotification(
            $tokens,
            [
                'title' => 'gogoPoint Refunded',
                'message' => 'Dear gogoUser, Your ' . $point . ' gogoReward point has been refunded.',
                'type' => 'gogoReward',
            ]
        );
        $notification->send();
    }
}
