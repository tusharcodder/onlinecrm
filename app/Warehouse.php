<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouses';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'name', 'country_code','is_shipped', 'address', 'city', 'postal_code', 'state', 'created_by', 'updated_by'
    ];
}
