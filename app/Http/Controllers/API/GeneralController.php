<?php

namespace App\Http\Controllers\API;

use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GeneralController extends Controller
{
    public function allCities()
    {
        $cities = City::all();
        if (count($cities) > 0)
            return response()->json(['status' => 1, 'msg' => 'done', 'data' => $cities]);
        else
            return response()->json(['status' => 0, 'msg' => 'no cities', 'data' => null]);
    }

    public function allDistricts()
    {
        $districts = District::all();
        if (count($districts)>0)
            return response()->json(['status' => 1 , 'msg' => 'done' , 'data' => $districts]);
        else
            return response()->json(['status' => 0 , 'msg' => 'no districts' , 'data' => null]) ;
    }

}
