<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    //
	protected $table = 'discounts';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'vendor_type', 'vendor_name', 'aggregator_vendor_name', 'product_code', 'discount', 'valid_from_date', 'valid_to_date', 'created_by', 'updated_by'
    ];
}