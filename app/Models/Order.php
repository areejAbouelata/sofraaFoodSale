<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = array('client_id', 'resturant_id', 'total', 'delivary_cost','status', 'payment_type', 'delivary_address');

    public function products()
    {
        return $this->belongsToMany('App\Models\Product','order_products');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Resturant');
    }

}