<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BoxIsbn extends Model
{
	protected $table = 'box_parent_isbns';
	 
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'box_isbn13', 'created_by', 'updated_by'
    ];
}
 