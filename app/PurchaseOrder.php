<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
     //
	protected $table = 'purchase_orders';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'bill_no', 'isbn13', 'purchase_date', 'vendor_id', 'book_title','purchase_by', 'quantity', 'mrp', 'discount', 'cost_price','created_by', 'updated_by','rack_location'
    ];
}
