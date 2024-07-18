<?php

namespace App\Http\Controllers\Api;

use App\DefaultConf;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{

    protected $conf = [];
    protected $paginationLimit = 10;
    protected $expiresAt = 60;

    public function __construct()
    {
        try {
            $myConf = DefaultConf::firstOrFail();
            $this->conf['nearby_radius'] = $myConf->rider_nearby_radius;
            $this->conf['free_delivery_after'] = $myConf->free_delivery_after;
            $this->conf['delivery_charge'] = $myConf->delivery_charge;
            $this->conf['delivery_charge_outside'] = $myConf->delivery_charge_outside;
            $this->conf['first_download_reward'] = $myConf->first_download_reward;
            $this->conf['referral_user_point'] = $myConf->referral_user_point;
            $this->conf['referred_user_point'] = $myConf->referred_user_point;
            $this->conf['user_refer_limit'] = $myConf->referred_user_point;
            $this->conf['cashback_percent'] = $myConf->cashback_percent;
            $this->conf['purchase_total'] = $myConf->purchase_total;
            $this->conf['user_app_version'] = $myConf->user_app_version;
            $this->conf['user_app_minor'] = $myConf->user_app_minor == 1;
            $this->conf['user_app_major'] = $myConf->user_app_major == 1;
            $this->conf['utility_promo'] = $myConf->utility_promo;
            $this->conf['reward_redeem_limit'] = $myConf->reward_redeem_limit;
            $this->conf['min_order_limit'] = $myConf->min_order_limit;
            $this->conf['user_refer_text'] = $myConf->user_refer_text;
            $this->conf['pool_bike_per_km_per_seat'] = $myConf->pool_bike_per_km_per_seat;
            $this->conf['pool_car_per_km_per_seat'] = $myConf->pool_car_per_km_per_seat;
        } catch (\Throwable $th) {
            $this->conf['nearby_radius'] = 5;
            $this->conf['free_delivery_after'] = 1000;
            $this->conf['delivery_charge'] = 100;
            $this->conf['delivery_charge_outside'] = 100;
            $this->conf['first_download_reward'] = 0;
            $this->conf['referral_user_point'] = 25;
            $this->conf['referred_user_point'] = 50;
            $this->conf['user_refer_limit'] = 255;
            $this->conf['cashback_percent'] = 0;
            $this->conf['purchase_total'] = 0;

            $this->conf['user_app_version'] = '';
            $this->conf['user_app_minor'] = false;
            $this->conf['user_app_major'] = false;

            $this->conf['utility_promo'] = '';
            $this->conf['reward_redeem_limit'] = 15;

            $this->conf['min_order_limit'] = 500;
            $this->conf['user_refer_text'] = "Refer your friend";
        }
    }
}
