<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function all(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(6);
        if ($notifications->first() == null || count($notifications) == 0) {
            return response()->json(['status' => 0, 'msg' => 'no notifications', 'data' => null]);
        } else {
            return response()->json(['status' => 1 , 'msg' => 'done' , 'dtat' => $notifications]);
        }
    }
}
