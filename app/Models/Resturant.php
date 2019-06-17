<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resturant extends Model
{

    protected $table = 'resturants';
    public $timestamps = true;
    protected $fillable = array('name', 'status', 'minimum', 'delivery_cost', 'delivery_way', 'rate', 'city', 'district', 'longitude', 'latitude', 'delivary_du_id', 'api_token', 'password', 'email', 'phone', 'whatsapp', 'photo');

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category','categories_orders');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function delivarytime()
    {
        return $this->belongsTo('App\Models\DelivaryTime', 'delivary_du_id');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City', 'city');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

}