<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pool extends Model
{
    //Status: 1=>active,2->cancel,0->inactive,3->ontheway,4->completed
    protected $fillable =   ['user_id','vehicle_id','current_location', 'desire_destination','location_lat','location_long','destination_lat','destination_long','date','time','distance_in_km','required_seat','vechical_type','is_recurring','recurring_strat_date','recurring_end_date','pool_type','status'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function vehicle(){
        return $this->belongsTo(UserVehicles::class, 'vehicle_id');
    }
    
}
