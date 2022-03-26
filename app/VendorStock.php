<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorStock extends Model
{
    //
	protected $table = 'vendor_stocks';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'isbnno', 'vendor_id', 'name', 'author', 'publisher', 'stock_date', 'binding_id', 'currency_id', 'price', 'discount', 'quantity', 'created_by', 'updated_by', 'temp_quantity'
    ];
}
