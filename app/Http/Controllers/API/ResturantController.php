<?php

namespace App\Http\Controllers\API;

use App\Models\Resturant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class ResturantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function login(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'msg' => 'not done',
                'data' => $validator->errors()
            ]);
        }
//        $pass = $request->password;

        if (count(Resturant::whereEmail($request->email)->get()) > 0) {
            $restaurant = Resturant::whereEmail($request->email)->first();
            if (Hash::check($request->password, $restaurant->password)) {
                return response()->json([
                    'status' => 1,
                    'msg' => 'ok',
                    'data' => $restaurant
                ]);
            }

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
        $validator = validator()->make($request->all(), [
            'name' => 'required',
            'city' => 'required|numeric',
            'district' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
//            'delivary_du_id' => 'required|numeric|exists:delivary_times,id',
            'delivary_du_id' => 'required|numeric',
            'delivary_way' => 'required',
            'minimum' => 'required',
            'delivery_cost' => 'required',
            'phone' => 'required',
            'whatsapp' => 'required',
            'photo' => 'required' ,
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'msg' => 'البيانات خاطئة',
                'data' => $validator->errors()
            ]);
        }
        $request->password = bcrypt($request->password);
        $request->merge(['api_token' => str_random(60), 'status' => 1]);
        $resturant = Resturant::create($request->all());
        return response()->json([
            'status' => 1,
            'msg' => 'تم يا وحش',
            'data' => [
                'restruant' => $resturant,
                'api_token' => $resturant->api_token
            ]

        ]);
    }


    public function show(Request $request)
    {
        $data = $request->user();
        return response()->json([
            'status' => 1,
            'msg' => 'done',
            'data' => $data
        ]);
    }

    public function toggleStatus(Request $request)
    {
        $validate = validator()->make($request->all(), [
            'status' => 'required|boolean'
        ]);
        if ($validate->fails())
            return response()->json([
                'status' => 0,
                'msg' => 'no'
            ]);
        else
            $rest = $request->user();
        $rest->status = $request->status;
        $rest->save();
        return $rest;

    }

    public function menu(Request $request)
    {
        $data = $request->user()->products()->paginate(5);
        return response()->json([
            'status' => 1,
            'msg' => 'done',
            'data' => $data
        ]);
    }

    public function commentsAndRating(Request $request)
    {
        $comments_rating = $request->user()->comments()->paginate(6);
        if ($comments_rating->first() == null || $comments_rating->count() == 0) {
            return response()->json(['status' => 0, 'msg' => 'no comment', 'data' => null]);
        } else {
            return response()->json(['status' => 1, 'msg' => 'done', 'data' => $comments_rating]);
        }
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
