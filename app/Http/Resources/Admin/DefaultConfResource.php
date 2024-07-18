<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class DefaultConfResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'riderNearbyRadius' => $this->rider_nearby_radius,
            'riderCredit' => $this->rider_credit,
            'freeDeliveryAfter' => $this->free_delivery_after,
            'deliveryCharge' => $this->delivery_charge,
            'deliveryChargeOutside' => $this->delivery_charge_outside,
            'nightSurgeStart' => $this->night_surge_start,
            'nightSurgeEnd' => $this->night_surge_end,
            'firstDownloadReward' => $this->first_download_reward,
            'referralUserPoint' => $this->referral_user_point,
            'referredUserPoint' => $this->referred_user_point,
            'cashbackPercent' => $this->cashback_percent,
            'purchaseTotal' => $this->purchase_total,
            'userAppVersion' => $this->user_app_version,
            'partnerAppVersion' => $this->partner_app_version,
            'userAppMinor' => $this->user_app_minor == 1,
            'userAppMajor' => $this->user_app_major == 1,
            'partnerAppMinor' => $this->partner_app_minor == 1,
            'partnerAppMajor' => $this->partner_app_major == 1,
            'riderFirstDownloadReward' => $this->rider_first_download_reward,
            'riderReferralUserPoint' => $this->rider_referral_user_point,
            'riderReferredUserPoint' => $this->rider_referred_user_point,
            'utilityPromo' => $this->utility_promo,
            'dineinCashback' => $this->dinein_cashback,
            'riderReferLimit' => $this->rider_refer_limit,
            'userReferLimit' => $this->user_refer_limit,
            'rewardRedeemLimit' => $this->reward_redeem_limit,
            'minOrderLimit' => $this->min_order_limit,
            'userReferText' => $this->user_refer_text,
            'poolBikePerKmPerSeat' => $this->pool_bike_per_km_per_seat,
            'poolCarPerKmPerSeat' => $this->pool_car_per_km_per_seat
        ];
    }
}
