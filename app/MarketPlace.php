<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketPlace extends Model
{
	protected $table = 'market_places';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'number', 'email', 'address', 'created_by', 'updated_by'
    ];
}
