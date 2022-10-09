<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UspsPrice extends Model
{
    protected $table = 'usps_price_details';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'tracking_no', 'volume_wgt','price', 'pack_wgt','zone_price'
    ];
}
