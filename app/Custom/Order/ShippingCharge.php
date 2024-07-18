<?php

namespace App\Custom\Order;

use App\DefaultConf;

class ShippingCharge
{
    public function __construct($totalPayment, $deliveryArea)
    {
        $this->totalPayment = $totalPayment;
        $this->deliveryArea = $deliveryArea;
    }

    public function calculateShipping()
    {
        $shippingCharge = 100;
        try {
            $defaultConf = DefaultConf::firstOrFail();

            if ($this->totalPayment >= $defaultConf->free_delivery_after) {
                if ($this->deliveryArea == "Outside Ring-Road (5KM)") {
                    $shippingCharge =  $defaultConf->delivery_charge_outside;
                } else {
                    $shippingCharge = 0;
                }
            } else {
                if ($this->deliveryArea == "Outside Ring-Road (5KM)") {
                    $shippingCharge =  $defaultConf->delivery_charge_outside;
                } elseif ($this->deliveryArea == "Inside Ring-Road") {
                    $shippingCharge =  $defaultConf->delivery_charge;
                }
            }
        } catch (\Throwable $th) {
            $shippingCharge = 100;
        }
        return $shippingCharge;
    }
}
