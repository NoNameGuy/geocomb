<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
	protected $table = 'station';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'brand', 'location', 'fuel_price', 'services', 'last_update', 'schedule'
    ];
    
}
