<?php

namespace App;

use App\RiderReferral;
use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Custom\Contracts\ImageableContract;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Driver extends Authenticatable implements JWTSubject, ImageableContract
{
    use Notifiable, Imageable, Routeable;

    // protected $guard = 'driver';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $guarded = ['id'];

    public $columnsWithTypes = [
        'first_name'        => 'string',
        'last_name'        => 'string',
        'dob'       => 'string',
        'email'       => 'string',
        'phone'       => 'string',
        'country_code'       => 'string',
        'password'    => 'password',
        'heard_from'        => 'string',
        'interested_in'        => 'string',
        'address'        => 'string',
        'lat'        => 'string',
        'long'        => 'string',
        'image'       => 'image',
        'gender'       => 'string',
        'rider' => 'boolean',
        'ondemand' => 'boolean',
        'is_blocked' => 'boolean',
        'blacklisted' => 'string',
        'service' => 'json',
        'license_expiry' => 'string',
        'bluebook_expiry' => 'string',
        'subscription' => "string",
        'is_associated_rider' => 'boolean',
        'refer_code' => 'string',
        'used_code' => 'string',
        'reward_point' => 'string',
        'last_login_at' => 'string',
        'from' => 'string',
    ];

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords($value);
    }

    public function isVerified()
    {
        return $this->verified == 1;
    }

    public function isPartiallyVerified()
    {
        return $this->verified == 2;
    }

    public function isBlocked()
    {
        return $this->is_blocked == 1;
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

    public function partiallyVerify()
    {
        $this->verified = 2;
        $this->save();
    }

    public function makeAssociated()
    {
        $this->is_associated_rider = !$this->is_associated_rider;
        $this->save();
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

    public function vehicleDetail()
    {
        return $this->hasOne(DriverVehicle::class);
    }

    // public function getInterestedInAttribute($value)
    // {
    //     return json_decode($value);
    // }

    public function status()
    {
        return $this->hasOne(DriverStatus::class);
    }

    public function devices()
    {
        return $this->hasMany(DriverDevice::class);
    }

    public function settlement()
    {
        return $this->hasOne(DriverPaymentSettlement::class);
    }

    public function completedTrips()
    {
        return $this->hasMany(Trip::class, 'driver_id')->where('status', 'completed');
    }

    public function completedDeliveries()
    {
        return $this->hasMany(Delivery::class, 'driver_id')->where('status', 'delivered');
    }

    public function cancelledTrips()
    {
        return $this->hasMany(Trip::class, 'driver_id')->where('status', 'cancelled');
    }

    public function tripHistories()
    {
        return $this->hasMany(Trip::class)->where(function ($query) {
            $query->where('status', 'completed')
                ->orWhere('status', 'cancelled')
                ->orWhere('status', 'disputed')
                ->orWhere('status', 'accident');
        })->latest();
    }

    public function deliveryHistories()
    {
        return $this->hasMany(Delivery::class)->where(function ($query) {
            $query->where('status', 'delivered')
                ->orWhere('status', 'cancelled')
                ->orWhere('status', 'collected');
        })->latest();
    }

    public function todayTotalAssigned()
    {
        return $this->hasMany(Delivery::class)->whereDate('deliveries.updated_at', '=', date("Y-m-d"));
    }

    public function todayTotalDelivered()
    {
        return $this->hasMany(Delivery::class)->where('deliveries.status', 'delivered')->whereDate('deliveries.updated_at', '=', date("Y-m-d"));
    }

    public function ratings()
    {
        return $this->hasMany(DriverRating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->count() > 0 ? round($this->ratings()->sum('rating') / $this->ratings()->count(), 2) : 0;
    }

    public function preference()
    {
        return $this->hasOne(MyPreference::class);
    }

    public function rentalTrips()
    {
        return $this->hasMany(RentalTrip::class, 'driver_id');
    }

    public function outstationTrips()
    {
        return $this->hasMany(OutstationTrip::class, 'driver_id');
    }

    /**
     * Get the address associated with the Driver
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(RiderHomeAddress::class, 'driver_id');
    }

    public function documentState()
    {
        if ($this->vehicleDetail) {
            $count = $this->vehicleDetail()->where(function ($q) {
                return $q
                    ->where('reg_no', '')
                    ->orWhereNull('reg_no')
                    ->orWhere('type', '')
                    ->orWhereNull('type')
                    ->orWhere('plate_no', '')
                    ->orWhereNull('plate_no')
                    ->orWhere('manufacturing_year', '')
                    ->orWhereNull('manufacturing_year')
                    ->orWhere('license_category', '')
                    ->orWhereNull('license_category')
                    ->orWhere('license_no', '')
                    ->orWhereNull('license_no')
                    ->orWhere('license', '')
                    ->orWhereNull('license')
                    ->orWhere('blue_book', '')
                    ->orWhereNull('blue_book')
                    ->orWhere('blue_book_sec', '')
                    ->orWhereNull('blue_book_sec')
                    ->orWhere('blue_book_trd', '')
                    ->orWhereNull('blue_book_trd')
                    ->orWhere('color', '')
                    ->orWhereNull('color')
                    ->orWhere('picture', '')
                    ->orWhereNull('picture');
            })->count();
            if ($count == 0) {
                $this->vehicleDetail()->update(['status' => 1]);
            }
            return $count;
        } else {
            return 1;
        }
    }

    public function vehicles()
    {
        return $this->belongsToMany(Vehicle::class);
    }

    public function myVehicle()
    {
        return $this->vehicles()->first();
    }

    public function myAddress($state)
    {
        $address = RiderHomeAddress::where('driver_id', $this->id)->first();

        if (!$address) {
            return '';
        }
        return $address->$state;
    }

    public function whoUsedMyCode()
    {
        return $this->hasMany(RiderReferral::class, 'driver_id');
    }

    public function AmIReferred()
    {
        return $this->hasOne(RiderReferral::class, 'used_by');
    }

    public function packages()
    {
        return $this->belongsToMany(SubscriptionPackage::class);
    }

    public function currentPackage()
    {
        return $this->packages()->first();
    }

    /**
     * Get the junction associated with the Driver
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function junction(): HasOne
    {
        return $this->hasOne(RiderJunction::class, 'driver_id');
    }

    /**
     * Get all of the myNotifications for the Driver
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function myNotifications(): HasMany
    {
        return $this->hasMany(DriverNotification::class, 'driver_id');
    }

    public function inHouseRiderPayment(): HasMany
    {
        # code...
        return $this->hasMany(InHouseRiderPaymentLog::class, 'driver_id')->where('received_by', NULL);
    }
}
