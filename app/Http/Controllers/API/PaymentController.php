<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
//        لحد دلوقتى دى لاوردرات الى انا ضامنه ان فلوسها ادفعت
        $orders = $request->user()->orders()->where('status' , 'delivered') ;
        $restaurant_selling = $orders->sum('total')+$orders->sum('delivary_cost') ;
        $app_percentage = $restaurant_selling/10;
//        2 vars 
    }
}
