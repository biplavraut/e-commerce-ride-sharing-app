<?php

namespace App;

use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Custom\Contracts\ImageableContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, ImageableContract
{
    use Notifiable, Imageable, Routeable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $columnsWithTypes = [
        'first_name'        => 'string',
        'last_name'        => 'string',
        'email'       => 'string',
        'dob'       => 'string',
        'gender'       => 'string',
        'password'    => 'password',
        'country_code'       => 'string',
        'phone'       => 'string',
        'phone1'       => 'string',
        'social_from' => 'string',
        'social_id'   => 'string',
        'email_token' => 'string',
        'verified'    => 'boolean',
        'phone_verified'    => 'boolean',
        'image'       => 'image',
        'heard_from'        => 'string',
        'office'        => 'string',
        'address'        => 'string',
        'lat'        => 'string',
        'long'        => 'string',
        'refer_code' => 'string',
        'used_code' => 'string',
        'reward_point' => 'string'
    ];

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords($value);
    }

    public function isPhoneVerified()
    {
        return $this->phone_verified == 1;
    }

    public function isVerified()
    {
        return $this->verified == 1;
    }

    public function verifyEmail()
    {
        $this->verified    = true;
        $this->email_token = null;
        $this->save();
    }

    public function verifyPhone()
    {
        $this->phone_verified    = true;
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

    public function qas()
    {
        return $this->hasMany(ProductQa::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReviewRating::class)->where('verified', 1);
    }

    public function savedPlaces()
    {
        return $this->hasMany(SavedPlace::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function scheduleTrips()
    {
        return $this->hasMany(ScheduleTrip::class);
    }

    public function pendingTrips()
    {
        return $this->hasMany(Trip::class)->where('status', 'pending');
    }

    public function ongoingTrips()
    {
        return $this->hasMany(Trip::class)->where('status', 'ongoing');
    }

    public function completedTrips()
    {
        return $this->hasMany(Trip::class)->where('status', 'completed');
    }

    public function tripHistories()
    {
        return $this->hasMany(Trip::class)->where(function ($query) {
            $query->where('status', 'completed')
                ->orWhere('status', 'cancelled');
        })->latest();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function rentalTrips()
    {
        return $this->hasMany(RentalTrip::class);
    }

    public function outstationTrips()
    {
        return $this->hasMany(OutstationTrip::class);
    }

    public function device()
    {
        return $this->hasOne(UserDevice::class);
    }

    public function vehicles()
    {
        return $this->hasMany(UserVehicles::class);
    }

    /**
     * Get all of the donations for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function donations(): HasMany
    {
        return $this->hasMany(MyDonation::class, 'user_id');
    }

    public function usedCouponCodes()
    {
        return $this->hasMany(CouponCodeHistory::class);
    }

    public function totalSaved()
    {
        $total = 0;
        foreach ($this->usedCouponCodes as $key => $history) {
            $total += $history->coupon->amount;
        }
        return $total;
    }

    public function userId()
    {
        $id = sprintf('%03d', $this->id);
        return "GGU" . date('Ymd', strtotime($this->created_at)) . "{$id}";
    }

    public function whoUsedMyCode()
    {
        return $this->hasMany(Referral::class, 'user_id');
    }

    public function AmIReferred()
    {
        return $this->hasOne(Referral::class, 'used_by');
    }

    public function gogoWallet()
    {
        return $this->hasOne(MyWallet::class, 'user_id');
    }
}
