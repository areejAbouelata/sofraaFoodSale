<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notefication extends Model 
{

    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = array('title', 'notification_date', 'action', 'content', 'action_id');

}