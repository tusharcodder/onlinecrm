<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTrack extends Model
{
    //
	protected $table = 'order_tracking';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'order_id', 'order_item_id', 'price', 'sku', 'isbnno', 'shipper', 'tracking_id', 'box_id', 'shipper_id', 'shipment_date', 'quantity_shipped', 'ncp', 'created_by', 'updated_by'
    ];
}
