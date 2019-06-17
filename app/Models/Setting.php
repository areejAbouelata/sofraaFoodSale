<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model 
{

    protected $table = 'settings';
    public $timestamps = true;
    protected $fillable = array('percentage', 'bank_acount', 'site_name', 'about_us', 'facebook_url', 'tweeter_url', 'insta_url', 'slogan', 'logo');

}