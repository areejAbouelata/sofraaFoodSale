<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //all products

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
            'discribtion' => 'required',
            'duration' => 'required',
            'price' => 'required',
            'photo' => 'image',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'msg' => 'validation fails',
                'data' => $validator->errors()->all()
            ]);
        } else {
            $product = $request->user()->products()->create($request->all());
            if ($request->hasFile('photo')) {
                $base_path = base_path() . '/uploads/products';
//                return $base_path;
                $photo = $request->photo;
                $extension = $photo->getClientOriginalExtension();
                $rename = time() . rand(0, 808080) . '.' . $extension;
                $photo->move($base_path, $rename);
                $product->update(['photo' => '/uploads/products/' . $rename]);
            }
            return response()->json([
                'status' => 1,
                'msg' => 'ok',
                'data' => $product->fresh()
            ]);
        }

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
     ]
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = validator()->make($request->all(), ['id' => 'required',
            'photo' => 'image',]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validation fails', 'data' => $validator->errors()->all()]);
        }else{
            $product = $request->user()->products()->find($request->id);
            $product->update($request->except('photo'));
            if ($request->hasFile('photo')){
                //  delete old photo from uploads
                if (is_file($product->photo))
                    unlink(base_path().$product->photo);
                //upload new photo
                $base_path = base_path() . '/uploads/products';
//                return $base_path;
                $photo = $request->photo;
                $extension = $photo->getClientOriginalExtension();
                $rename = time() . rand(0, 808080) . '.' . $extension;
                $photo->move($base_path, $rename);
                $product->update(['photo' => '/uploads/products/' . $rename]);
            }
            return response()->json(['status' => 1 , 'msg' => 'done' , 'data' => $product->fresh()]);
        }


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
