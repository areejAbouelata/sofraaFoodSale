<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model 
{

    protected $table = 'offers';
    public $timestamps = true;
    protected $fillable = array('title', 'resturant_id', 'hint', 'photo' ,'date_from', 'date_to');

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

}