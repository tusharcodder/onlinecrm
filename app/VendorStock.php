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
       'isbnno', 'vendor_name', 'name', 'author', 'publisher', 'stock_date', 'binding_type', 'currency', 'price', 'discount', 'quantity', 'created_by', 'updated_by'
    ];
}
