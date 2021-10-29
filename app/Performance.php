<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    //
	protected $table = 'performances';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'product_code', 'category', 'sale_through', 'created_by', 'updated_by'
    ];
}