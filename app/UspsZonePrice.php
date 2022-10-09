<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UspsZonePrice extends Model
{
    protected $table = 'usps_zone_price_list';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'zone_price', 'zone_id','lbs_wgt_to','lbs_wgt_from','created_by', 'updated_by'
    ];
}
