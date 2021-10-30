<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Binding extends Model
{
    protected $table = 'bindings';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'created_by', 'updated_by'
    ];
}
