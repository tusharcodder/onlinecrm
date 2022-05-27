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
       'order_id', 'order_item_id', 'purchase_date', 'payments_date', 'reporting_date', 'promise_date', 'days_past_promise', 'buyer_email', 'buyer_name', 'buyer_phone_number', 'sku', 'product_name', 'quantity_purchased', 'quantity_shipped', 'quantity_to_ship', 'quantity_to_be_shipped', 'shipper_book_isbn', 'warehouse_id', 'warehouse_name', 'warehouse_country_code', 'warehouse_rack_details', 'price', 'selling_price', 'shipping_price', 'ship_service_level', 'recipient_name', 'ship_address_1', 'ship_address_2', 'ship_address_3', 'ship_city', 'ship_state', 'ship_postal_code', 'ship_country', 'is_business_order', 'purchase_order_number', 'price_designation', 'status', 'created_by', 'updated_by', 'deleted_by', 'deleted_at', 'label_id', 'label_date', 'pdf_attachment_code', 'label_pdf_url', 'tracking_number'
    ];
}