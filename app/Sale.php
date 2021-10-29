<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
	protected $table = 'sales';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'sale_date', 'invoice_no', 'po_no', 'brand', 'category', 'vendor_type', 'vendor_name', 'aggregator_vendor_name', 'hsn_code', 'sku_code', 'product_code', 'colour', 'size', 'quantity','vendor_discount', 'mrp', 'before_tax_amount', 'state', 'cgst', 'sgst', 'igst', 'sale_price', 'total_sale_amount', 'cost_price', 'total_cost_amount', 'receivable_amount', 'created_by', 'updated_by'
    ];
}
