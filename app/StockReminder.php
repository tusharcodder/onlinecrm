<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockReminder extends Model
{
    protected $table = 'stock_reminders';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'low_stock_threshold', 'out_of_stock_threshold', 'created_by', 'updated_by'
    ];
}
