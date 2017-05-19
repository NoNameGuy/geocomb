<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
	protected $table='district';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'station'
    ];

    

}
