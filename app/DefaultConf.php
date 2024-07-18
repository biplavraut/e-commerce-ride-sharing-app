<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DefaultConf extends Model
{
    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'rider_nearby_radius'             => 'string',
        'rider_credit'             => 'string',
        'free_delivery_after'             => 'string',
        'delivery_charge'             => 'string',
        'delivery_charge_outside' => 'string',
        'night_surge_start'             => 'string',
        'night_surge_end'             => 'string',
        'first_download_reward' => 'string',
        'referral_user_point' => 'string',
        'referred_user_point' => 'string',
        'cashback_percent' => 'string',
        'purchase_total' => 'string',
        'user_app_version' => 'string',
        'partner_app_version' => 'string',
        'user_app_minor' => 'boolean',
        'user_app_major' => 'boolean',
        'partner_app_minor' => 'boolean',
        'partner_app_major' => 'boolean',
        'rider_first_download_reward' => 'string',
        'rider_referral_user_point' => 'string',
        'rider_referred_user_point' => 'string',
        'utility_promo' => 'string',
        'dinein_cashback' => 'string',
        'rider_refer_limit' => 'string',
        'user_refer_limit' => 'string',
        'reward_redeem_limit' => 'string',
        'min_order_limit' => 'string',
        'user_refer_text' => 'string',
        'pool_bike_per_km_per_seat' => 'string',
        'pool_car_per_km_per_seat' => 'string'
    ];
}
