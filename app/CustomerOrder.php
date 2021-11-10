<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    //
	protected $table = 'customer_orders';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'order_id', 'order_item_id', 'purchase_date', 'payments_date', 'reporting_date', 'promise_date', 'days_past_promise', 'buyer_email', 'buyer_name', 'buyer_phone_number', 'sku', 'product_name', 'quantity_purchased', 'quantity_shipped', 'quantity_to_ship', 'ship_service_level', 'recipient_name', 'ship_address_1', 'ship_address_2', 'ship_address_3', 'ship_city', 'ship_state', 'ship_postal_code', 'ship_country', 'is_business_order', 'purchase_order_number', 'price_designation', 'created_by', 'updated_by'
    ];
}
