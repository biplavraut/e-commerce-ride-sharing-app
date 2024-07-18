<?php

namespace App\Services;

use App\Order;
use Exception;
use Carbon\Carbon;
use App\DefaultConf;
use Illuminate\Support\Str;
use App\Helper\ResponseMessage;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Api\OrderRequest;
use App\OrderOfferConf;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Subtotal;

class OrderService extends ModelService
{
    const MODEL = Order::class;

    public function getForIndex($limit = 20, $columns = ['*'])
    {
        $orders = $this->query()->oldest()->paginate($limit, $columns);
    }

    public function insert(OrderRequest $request)
    {
        try {
            DB::beginTransaction();
            $user = auth()->guard('api')->user();
            $refNumber = "GGRef" . $user->id . date("Ymd") . rand(11111, 99999);
            $vendorIds = $orders = [];
            //check for product's vendor and create package
            foreach ($user->cart as $item) {
                $vendorIds[] = $item->product->vendor_id;
            }
            $uniqueVendorIds = array_unique($vendorIds);
            foreach ($uniqueVendorIds as  $value) {
                $order                  =    new Order();
                $order->user_id         =   $user->id;
                $order->vendor_id       =   $value;
                $order->subtotal        =   0;
                $order->shipping_fee    =   0;
                $order->total           =   0;
                $order->location        =   $request->address;
                $order->delivery_location = $request->deliveryArea;
                $order->lat             =   $request->latitude;
                $order->long            =   $request->longitude;
                $order->order_by        =   $user->first_name . " " . $user->last_name;
                $order->phone           =   $user->country_code . $user->phone;
                $order->email           =   $user->email;
                $order->status          =   "PENDING";
                $order->payment_mode    =   $request->paymentType == "cod" ? "cash on delivery" : $request->paymentType;
                $order->ref_number      =    $refNumber;
                $order->nearest_landmark      =    $request->nearestLandmark;
                $order->takeaway      =    $request->takeaway ?? 0;
                $order->special_instruction      =    $request->specialInstruction;
                $order->alternate_name      =    $request->alternateName;
                $order->alternate_phone      =    $request->alternatePhone;
                $order->otp = $request->takeaway == 1 ? randomNumericString(4) : null;
                $order->save();
                $orders[] = $order;
            }

            DB::commit();
            return $orders;
        } catch (Exception $e) {
            DB::rollBack();
            return ResponseMessage::ERROR;
        }
    }

    public function updateTotal($orders)
    {
        $orderItems = count($orders);
        $i = 0;
        $shippingApplicable = 0;
        $wholeTotal = 0;
        foreach ($orders as $order) {
            $subtotal   =   0;
            foreach ($order->orderItems as $key => $orderItem) {
                // order Item Price
                // Marked Price is  $orderItem->price;
                $discountType = $orderItem->product->discount_type;
                $itemDiscount = ($orderItem->product->discount_type == 'percent') ? round($orderItem->product->price * ($orderItem->product->discount / 100)) : round($orderItem->product->discount);
                $discount = $orderItem->product->discount;
                // Check Active order offers
                $vendorOrderOfferApplicable = $orderItem->vendor->order_offer_applicable == 1;
                $offerActive = false;
                $offerDiscount = 0;
                $activeOrderOffer = OrderOfferConf::where('enabled', 1)->where('from', '<=', date('Y-m-d H:i:s'))->where('to', '>', date('Y-m-d H:i:s'))->first();
                if ($activeOrderOffer && $vendorOrderOfferApplicable) {
                    // Check if user is eligible for offer
                    $countOrders = Order::where('user_id', $order->user->id)
                        ->distinct()
                        ->where('status', '!=', 'CANCELLED')
                        ->where('ref_number', '!=', $order->ref_number)
                        ->where('created_at', '>=', $activeOrderOffer->from)
                        ->where('created_at', '<=', $activeOrderOffer->to)->count('ref_number');
                    $remainingOrders = $activeOrderOffer->no_of_orders - $countOrders;

                    if ($remainingOrders > 0) {
                        $offerActive = true;
                        $offerDiscount = $activeOrderOffer->discount;
                    } else {
                        $offerActive = false;
                        $offerDiscount = $activeOrderOffer->discount;
                    }
                }
                if ($offerActive) {
                    $offerDiscountPrice = round(($orderItem->product->price * $offerDiscount) / 100);
                    if ($offerDiscountPrice > $itemDiscount) {
                        $itemDiscount = $offerDiscountPrice;
                        $discountType       = 'percent';
                        $discount   = $offerDiscount;
                    }
                }
                $sellingPrice = $orderItem->price - round($itemDiscount);
                $eliteDiscount = 0;
                if ($order->user->elite == 1) {
                    $eliteDiscount = $orderItem->elite_price;
                }
                // Final Selling Price of product per pieces without tax and VAT
                $finalSellingPrice  =   $sellingPrice - $eliteDiscount;
                $serviceChargePerProduct = round(($finalSellingPrice * $orderItem->service_charge_amt) / 100);
                $vatPerProduct = round((($finalSellingPrice + $serviceChargePerProduct) * $orderItem->tax_amt) / 100);
                $orderItemUpdated = $orderItem->update(['tax_amt' => $vatPerProduct, 'service_charge_amt' => $serviceChargePerProduct, 'discount' => $discount, 'discount_type' => $discountType]);
                $itemSubTotal = ($finalSellingPrice + $vatPerProduct + $serviceChargePerProduct) * $orderItem->quantity;
                $subtotal += $itemSubTotal;
                $shippingApplicable += $subtotal;
            }
            $shippingCharge = 0;
            $order->subtotal        =   $subtotal;
            $order->shipping_fee = $shippingCharge;
            $order->total   =   $subtotal + $shippingCharge;
            $order->save();
            $wholeTotal += $order->total;
            $i++;
        }
        return $wholeTotal;
    }

    public function updatePayingTotal($orders, $shippingCharge)
    {
        $count = 0;
        foreach ($orders as $order) {
            $subtotal   =   0;
            foreach ($order->orderItems as $key => $orderItem) {
                $itemTotal          =   $orderItem->gogo_price * $orderItem->quantity;
                $subtotal +=  $itemTotal;
            }
            if ($count == 0) {
                $order->shipping_fee           =   $shippingCharge; //Assigining shipping charge to first order
                $order->total = $order->total + $shippingCharge;
            }
            $order->paying_total           =   $subtotal;
            $order->save();
            $count++;
        }
    }

    public function getAdminAdvancedData($name)
    {
        if (!$name) {
            return collect([]);
        }

        if (Str::contains($name, 'GGO')) {
            $id = (int)substr($name, 11);

            return $orders = $this->query()->where('id', $id)->paginate(10);
        }


        return $this->query()->where('accepted', 1)
            ->Where('location', 'LIKE', '%' . $name . '%')
            ->orWhere('order_by', 'LIKE', $name . '%')
            ->orWhere('status', 'LIKE', $name . '%')
            ->orWhere('payment_mode', 'LIKE', $name . '%')
            ->orWhere('ref_number', 'LIKE', $name . '%')
            ->orWhere('date', 'LIKE', $name . '%')
            ->paginate(10);
    }

    public function getAdvancedData($name)
    {
        if (!$name) {
            return collect([]);
        }

        $byPhone = $this->query()->where('vendor_id', auth()->id())->where('phone', '+' . $name)->paginate(10);

        if ($byPhone->count() > 0) {
            return $byPhone;
        }

        $byName = $this->query()->where('vendor_id', auth()->id())->where('order_by', $name)->paginate(10);


        if ($byName->count() > 0) {
            return $byName;
        }

        if (Str::contains($name, 'GGO')) {
            $id = (int)substr($name, 11);

            return $orders = $this->query()->where('vendor_id', auth()->id())->where('id', $id)->paginate(10);
        }


        return $this->query()->where('vendor_id', auth()->id())
            ->Where('location', 'LIKE', '%' . $name . '%')
            ->orWhere('order_by', 'LIKE', $name . '%')
            ->orWhere('status', 'LIKE', $name . '%')
            ->orWhere('payment_mode', 'LIKE', $name . '%')
            ->orWhere('ref_number', 'LIKE', $name . '%')
            ->orWhere('date', 'LIKE', $name . '%')
            ->paginate(10);
    }

    public function getAcceptedCount()
    {
        return $this->query()->where('status', '!=', 'DELIVERED')->where('status', '!=', 'CANCELLED')->count();
    }

    public function getVendorOrderCount()
    {
        return $this->query()->where('vendor_id', auth()->id())->Where('status', 'PENDING')->count();
    }

    public function getVendorTakeawayOrderCount()
    {
        return $this->query()->where('vendor_id', auth()->id())->where('takeaway', 1)->where('accepted', 1)->Where('status', 'PENDING')->count();
    }
}
