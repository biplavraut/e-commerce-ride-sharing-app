<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

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

    public function device()
    {
        return $this->hasOne(DriverDevice::class);
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
                ->orWhere('status', 'cancelled');
        })->latest();
    }

    public function deliveryHistories()
    {
        return $this->hasMany(Delivery::class)->where(function ($query) {
            $query->where('status', 'delivered')
                ->orWhere('status', 'cancelled');
        })->latest();
    }

    public function todayTotalAssigned()
    {
        return $this->hasMany(Delivery::class)->whereDate('deliveries.updated_at','=',date("Y-m-d"));
    }

    public function todayTotalDelivered()
    {
        return $this->hasMany(Delivery::class)->where('deliveries.status', 'delivered')->whereDate('deliveries.updated_at','=',date("Y-m-d"));
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
}
