<?php

namespace App;

use App\Role;
use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Custom\Contracts\ImageableContract;
use App\Http\Resources\Admin\VendorAdvanceSettlementResource;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Vendor extends Authenticatable implements JWTSubject, ImageableContract
{
    use Notifiable, Imageable, Routeable;

    protected $guard = 'vendor';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $from = null;
    protected $to = null;

    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'business_name'        => 'string',
        'first_name'        => 'string',
        'last_name'        => 'string',
        'email'       => 'string',
        'phone'       => 'string',
        'country_code'       => 'string',
        'password'    => 'password',
        'type'        => 'string',
        'verified' => 'boolean',
        'partnership_type'        => 'string',
        'city'        => 'string',
        'address'        => 'string',
        'area'        => 'string',
        'lat'        => 'string',
        'long'        => 'string',
        'image'       => 'image',
        'is_hidden'       => 'boolean',
        'order_offer_applicable'       => 'boolean',
        'status'       => 'boolean',
        'takeaway'       => 'boolean',
        'dine_in'       => 'boolean',
        'settlement_time' => 'string',
        'radius_limit' => 'string',
        'from' => 'string',
    ];

    public function setBusinessNameAttribute($value)
    {
        $this->attributes['business_name'] = ucwords($value);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords($value);
    }

    public function setLatAttribute($value)
    {
        $this->attributes['lat'] = trim($value);
    }

    public function setLongAttribute($value)
    {
        $this->attributes['long'] = trim($value);
    }

    public function isVerified()
    {
        return $this->verified == 1;
    }

    public function isPhoneVerified()
    {
        return $this->phone_verified == 1;
    }

    public function isEmailVerified()
    {
        return $this->email_verified == 1;
    }

    public function verifyEmail()
    {
        $this->email_verified = true;
        $this->email_token = null;
        $this->save();
    }


    public function verifyPhone()
    {
        $this->phone_verified = true;
        $this->save();
    }

    public function verify()
    {
        $this->verified = true;
        $this->save();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function hasRole($role_name)
    {
        foreach ($this->getCachedRoles(30) as $role) {
            if ($role->name == $role_name) {
                return true;
            }
        }

        return false;
    }

    public function has_role($role_id)
    {
        foreach ($this->getCachedRoles(30) as $role) {
            if ($role->id == $role_id) {
                return true;
            }
        }

        return false;
    }

    public function hasOnlyRole($role_name)
    {
        $roles = $this->getCachedRoles(30);

        return (count($roles) === 1 && $roles->first()->name == $role_name);
    }

    private function getCachedRoles($minutes)
    {
        return Cache::remember("vendor.{$this->id}.roles", $minutes, function () {
            return $this->roles;
        });
    }

    public function services()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function hasService($service_name)
    {
        foreach ($this->getCachedServices(30) as $service) {
            if ($service->name == $service_name) {
                return true;
            }
        }

        return false;
    }

    public function has_service($service_id)
    {
        foreach ($this->getCachedServices(30) as $service) {
            if ($service->id == $service_id) {
                return true;
            }
        }

        return false;
    }

    public function hasOnlyService($service_name)
    {
        $services = $this->getCachedServices(30);

        return (count($services) === 1 && $services->first()->name == $service_name);
    }

    private function getCachedServices($minutes)
    {
        return Cache::remember("vendor.{$this->id}.services", $minutes, function () {
            return $this->services;
        });
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
        // return $this->hasMany(Product::class)->whereHas("vendor", function ($query) {
        //     $query->where("status", 1)->Where('verified', 1);
        // });
    }

    public function serviceProducts($serviceId, $limit = 20)
    {
        $products = $this->hasMany(Product::class)->where('verified', 1)->paginate($limit)->appends(request()->query());

        $collection = $products->getCollection();
        $filteredCollection = $collection->filter(function ($model) use ($serviceId) {
            return $model->service->id == $serviceId;
        });
        return $products->setCollection($filteredCollection);
    }


    public function qas()
    {
        return $this->hasMany(ProductQa::class)->where('answer', null)->orWhere('answer', '');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReviewRating::class)->where('verified', 0);
    }

    public function vendorId()
    {
        $id = sprintf('%03d', $this->id);
        return "GGV" . date('Ymd', strtotime($this->created_at)) . "{$id}";
    }

    public function vendorRealId()
    {
        return $this->id;
    }

    /**
     * Get the scheduleTime associated with the Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function scheduleTime(): HasOne
    {
        return $this->hasOne(openingTiming::class, 'vendor_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function settleLogs()
    {
        return $this->hasMany(VendorSettleLog::class);
    }

    public function orderTotalCOD()
    {
        $this->fromTo();
        return $this->orders()->whereBetween('date', [$this->from, $this->to])->where('status', 'DELIVERED')->where('payment_mode', 'cash on delivery')->sum('paying_total') / 100;
    }

    public function orderTotalDIGITAL()
    {
        $this->fromTo();

        return $this->orders()->whereBetween('date', [$this->from, $this->to])->where('status', 'DELIVERED')->where('payment_mode', '!=', 'cash on delivery')->Where('payment_mode', '!=', 'gogoWallet')->sum('paying_total') / 100;
    }

    public function orderTotalWallet()
    {
        $this->fromTo();

        return $this->orders()->whereBetween('date', [$this->from, $this->to])->where('status', 'DELIVERED')->where('payment_mode', 'gogoWallet')->sum('paying_total') / 100;
    }

    public function countOrder()
    {
        $this->fromTo();

        return $this->orders()->whereBetween('date', [$this->from, $this->to])->where('status', 'DELIVERED')->count();
    }

    public function fromTo()
    {
        // if (!$this->settleLogs()->latest()->first()) {
        //     $this->to = date('Y-m-d');
        // $this->from = date('Y-m-d', strtotime($this->to . ' - 30 days'));
        // } else {
        //     $this->from = date('Y-m-d', strtotime($this->settleLogs()->latest()->first()->created_at));
        //     $this->to = date('Y-m-d');
        // }

        $settleDays = $this->settlement_time;

        if ($this->settleLogs()->latest()->first()) {
            $this->from = date('Y-m-d', strtotime($this->settleLogs()->latest()->first()->created_at));
            $this->to = date('Y-m-d', strtotime($this->from . ' -' . $settleDays . ' days'));
        } else {
            $this->to = date('Y-m-d');
            $this->from = date('Y-m-d', strtotime($this->to . ' -' . $settleDays . ' days'));
        }

        return [$this->from, $this->to];
    }

    public function vendorOptions()
    {
        return $this->hasMany(VendorOption::class);
    }

    public function showOpeningClosing()
    {
        $statuses = $this->services()->pluck('opening_closing_time');
        $true = 0;
        $false = 0;

        foreach ($statuses as $key => $status) {
            if ($status == 1) {
                $true = $true + 1;
            } else {
                $false = $false + 1;
            }
        }

        if ($true > $false) {
            return true;
        } else {
            return false;
        }
    }

    public function ownReviews()
    {
        return $this->hasMany(VendorReviewRating::class)->where('verified', 1);
    }

    public function averageRating()
    {
        return $this->ownReviews()->count() > 0 ? round($this->ownReviews()->sum('rating') / $this->ownReviews()->count(), 2) : 0;
    }

    // Settlements

    public function advanceLogs()
    {
        return $this->hasMany(VendorAdvanceSettlement::class);
    }

    public function vendorAdvanceSettlement()
    {
        return $this->hasMany(VendorAdvanceSettlement::class)->where('status', 0);
    }

    public function vendorAdvanceSettlementBalance()
    {
        $advance = $this->hasMany(VendorAdvanceSettlement::class)->select(DB::raw('sum(amount) as amount, sum(settled_amount) as settled_amount'))->where('status', 0)->first();
        $total = $advance->amount - $advance->settled_amount;
        return $total;
    }

    public function with_in_radius($lat, $long)
    {
        if ($lat && $long && $this->radius_limit) {

            $distance =  number_format((float) getDistance($this->lat, $this->long, $lat, $long), 2, '.', '');

            if ($distance <= $this->radius_limit) {
                return true;
            }
            return false;
        }
        return true;
    }


    /**
     * Get all of the myNotifications for the Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myNotifications(): HasMany
    {
        return $this->hasMany(VendorNotification::class, 'vendor_id');
    }

    public function dineInForms()
    {
        return $this->hasMany(DineInForm::class);
    }

    public function devices()
    {
        return $this->hasMany(VendorDevice::class);
    }
}
