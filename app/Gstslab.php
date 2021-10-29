<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gstslab extends Model
{
	 //
	protected $table = 'gstslabs';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'amount_from', 'amount_to', 'gst_per','created_by', 'updated_by'
    ];
}