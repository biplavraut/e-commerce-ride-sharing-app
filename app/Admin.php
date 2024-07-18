<?php

namespace App;

use App\Custom\Contracts\ImageableContract;
use App\Custom\Traits\Imageable;
use App\Custom\Traits\Routeable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements ImageableContract
{
    use Notifiable, Imageable, Routeable;

    protected $guard = 'admin';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $guarded = [];

    public $columnsWithTypes = [
        'name'        => 'string',
        'email'       => 'string',
        'phone'       => 'string',
        'country_code'       => 'string',
        'password'    => 'password',
        'verified'    => 'string',
        'verified_at' => 'datetime',
        'type'        => 'string',
        'image'       => 'image',
    ];

    public function isVerified()
    {
        return $this->verified == 1;
    }

    public function isPhoneVerified()
    {
        return $this->phone_verified == 1;
    }

    public function hasRole($role)
    {
        return $this->type === $role;
    }

    public function isSuperadmin()
    {
        return $this->hasRole('superadmin');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }
}
