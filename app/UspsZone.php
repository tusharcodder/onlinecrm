<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UspsZone extends Model
{
    protected $table = 'usps_zone';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'zone_id', 'zip_code','created_by', 'updated_by'
    ];
}
