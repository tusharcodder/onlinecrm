<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
	protected $table = 'stocks';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'manufacturer_name', 'country', 'manufacture_date', 'cost', 'stock_date', 'brand', 'category', 'gender', 'colour', 'size', 'lotno', 'sku_code', 'product_code', 'hsn_code', 'online_mrp', 'offline_mrp', 'description', 'image_url', 'quantity', 'created_by', 'updated_by'
    ];
}
