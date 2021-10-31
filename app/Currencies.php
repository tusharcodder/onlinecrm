<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
	protected $table = 'currenciess';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'symbol', 'created_by', 'updated_by'
    ];
}
