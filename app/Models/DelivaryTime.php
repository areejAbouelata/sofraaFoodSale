<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DelivaryTime extends Model 
{

    protected $table = 'delvary_times';
    public $timestamps = true;
    protected $fillable = array('duration', 'from', 'to');

    public function resturants()
    {
        return $this->hasMany('App\Models\Resturant');
    }

}