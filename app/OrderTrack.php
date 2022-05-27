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
       'order_id', 'order_item_id', 'price', 'selling_price', 'shipping_price', 'sku', 'isbnno', 'shipper_book_isbn', 'shipper', 'warehouse_id', 'warehouse_name', 'rack_details', 'box_shipper_id', 'shipper_tracking_id', 'box_id', 'shipper_id', 'shipment_date', 'quantity_shipped', 'ncp', 'created_by', 'updated_by'
    ];
}