<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicle';
    public $timestamps = false;
    public function fuels()
    {
        return $this->hasMany('App\Fuels');
    }
}
