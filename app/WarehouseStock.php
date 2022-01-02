<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseStock extends Model
{
    //
    protected $table = 'warehouse_stocks';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'warehouse_id', 'isbn13','quantity'
    ];
}
