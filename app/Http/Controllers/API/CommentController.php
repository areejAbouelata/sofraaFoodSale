<?php

namespace App\Http\Controllers\API;

use App\Models\Comment;
use App\Models\Resturant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function create(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'resturant_id' => 'required|numeric',
            'content' => 'required|string',
            'rate' => 'required|numeric|max:5|min:0'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'invalid validation', 'data' => null]);
        } else {
            $comment = $request->user()->comments()->create($request->all());
//            update restaurant rating as all her
            $restaurant = Resturant::where('id', $request->resturant_id)->first();
            $restaurant_rate = $restaurant->comments()->avg('rate');
            $all_rates = Comment::all()->avg('rate');
            $restaurant->rate =  ($restaurant_rate/ $all_rates)*100;
            $restaurant->save();
//            return $restaurant->rate;
            return response()->json(['status' => 1, 'msg' => 'done', 'data' => $comment]);
        }
    }

    public function allComments(Request $request)
    {
        $validator = validator()->make($request->all(), ['id' => 'required|numeric']);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'invalid id', 'data' => $validator->errors()->all()]);
        } else {
            $restaurant = Resturant::find($request->id);
            $comments = $restaurant->comments()->paginate(6);
            return response()->json(['status' => 1, 'msg' => 'done comments', 'data' => $comments]);
        }
    }
}
