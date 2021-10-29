<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendors';
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'vendor_name', 'contact_person_name', 'contact_person_number', 'contact_person_email', 'commission_type', 'commission', 'created_by', 'updated_by'
    ];
}
