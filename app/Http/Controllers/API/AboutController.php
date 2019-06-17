<?php

namespace App\Http\Controllers\API;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::get()->first();
        if ($setting == null || count($setting) == 0) {
            return response()->json([
                'status' => 0,
                'msg' => 'bad job',
                'data' => null
            ]);
        } else {
            return response()->json([
                'status' => 1,
                'msg' => 'done',
                'data' => $setting->about_us
            ]);
        }
    }

    public function socialMedia(Setting $setting)
    {
        $setting = $setting->get()->first();
       if ($setting == null || count($setting) == 0){
           return response()->json([
               'status' => 0 ,
               'msg' => 'not done' ,
               'data' => null
           ]);
       }
        else{
            $data = [
                'facebook' => $setting->facebook_url,
                'tweeter' => $setting->tweeter_url,
                'insta' => $setting->insta_url
            ];
            return response()->json([
                'status' => 1 ,
                'msg' => 'done' ,
                'data' => $data
            ]) ;
            }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
