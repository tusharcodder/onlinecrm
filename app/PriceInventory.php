<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceInventory extends Model
{
    //
	protected $table = 'price_inventory';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'price', 'quantity', 'sku','lead_time',
       ];
}