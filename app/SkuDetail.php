<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkuDetail extends Model
{
    protected $table = 'skudetails';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'market_id', 'warehouse_id', 'isbn13', 'isbn10', 'mrp','sku_code','disc','wght','pkg_wght','oz_wt','type','created_by', 'updated_by'
    ];
}
