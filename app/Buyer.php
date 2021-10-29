<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
	
	protected $table = 'buyers';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'country', 'address', 'created_by', 'updated_by',
    ];
}
