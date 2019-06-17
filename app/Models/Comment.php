<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model 
{

    protected $table = 'comments';
    public $timestamps = true;
    protected $fillable = array('client_id', 'resturant_id', 'content', 'rate');

    public function resturant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

}