<?php

namespace App\Http\Controllers\API;

use App\Mail\SendMailable;
use App\Models\Resturant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use  App\Notifications\PasswordRestRequest;
use  App\Notifications\PasswordResetSuccess;
use  App\Models\Client;
use App\PasswordReset;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;


class PasswordResetController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email'
        ]);
        $user = Client::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 0, 'msg' => 'this email does not exist', 'data' => null]);
        }

        $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email], [
            'email' => $user->email,
            'token' => str_random(10)
        ]);
        if ($user && $passwordReset) {
//            $user->notify(new PasswordRestRequest($passwordReset->token));
//            or send email here
//            return $user->email;
//            Mail::to($user->email)->from('areejibrahim222@gmail.com')
//                ->send(new SendMailable($passwordReset->token));
//            return Mail::failures();

//            return 'email sent' ;

            Mail::send('emails.name', ['data' => $passwordReset->token], function ($message) use ($user) {
                $message->from('areejibrahim222@gmail.com');
                $message->to($user->email, 'sofraa')->subject('Welcome!');
            });
        }
        return response()->json(['status' => 1, 'msg' => 'you have email your password reset link', 'data' => null]);
    }

    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset) {
            return response()->json(['status' => 0, 'msg' => 'this password reset token is invalid', 'data' => null]);
        }
        if (Carbon::parse($passwordReset->updated_at)->addMinute(720)->isPast()) {
            $passwordReset->delete();
            return response()->json(['status' => 0, 'msg' => 'this password reset token is invalid', 'data' => null]);
        }
        return response()->json([
            'status' => 1,
            'msg' => 'done',
            'data' => $passwordReset
        ]);

    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        $passwordReset = PasswordReset::where([['email' => $request->email], ['token' => $request->token]])->first();
        if (!$passwordReset) {
            return response()->json([
                'status' => 0,
                'msg' => 'this password token invalid',
                'data' => null
            ]);
            $user = Client::where('email', $passwordReset->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => 0,
                    'msg' => 'no client with that email',
                    'data' => null
                ]);
            }
            $user->password = bcrypt($request->password);
            $user->save();
            $passwordReset->delete();
//            $user->notify(new PasswordResetSuccess($passwordReset));
            return response()->json([
                'status' => 1,
                'msg' => 'done',
                'data' => $user
            ]);
        }
    }

    public function restaurantCreateToken(Request $request)
    {
        /**
         * 1-take email  validate it* 2-search for email in restaurant* 3-create rr update token in password_reset* 4- if user is exist + password  is exist send email to restaurant* 5-return with the ok status
         **/
        $validator = validator()->make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails())
            return response()->json(['status' => 0, 'msg' => 'validation error', 'data' => $validator->errors()->all()]);

        $user = Resturant::where('email', $request->email)->first();
        if (!$user)
            return response()->json(['status' => 0, 'msg' => 'this email does not exist', 'data' => null]);
        $resetPassword = PasswordReset::updateOrCreate(['email' => $user->email], [
            'email' => $user->email,
            'token' => str_random(15)
        ]);
        if ($user && $resetPassword)
            Mail::send('emails.name', ['data' => $resetPassword->token], function ($message) use ($user) {
                $message->from('areejibrahim222@gmail.com');
                $message->to($user->email)->subject('sofraa reset password');
            });

        return response()->json(['status' => 1, 'msg' => 'check your inbox', 'data' => null]);
    }

    public function restaurantFindToken($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset)
            return response()->json(['status' => 0, 'msg' => 'invalid token', 'data' => null]);
        if (Carbon::parse($passwordReset->updated_at)->addMinute(720)->isPast())
            return response()->json(['status' => 0, 'msg' => 'invalid token ', 'data' => null]);
            $passwordReset->delete();
        return response()->json([
            'status' => 1,
            'msg' => 'here you are your token',
            'data' => $passwordReset
        ]);
    }

    public function restRestaurantPassword(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validation error', 'data' => $validator->errors()->all()]);
        }
        $passwordReset = PasswordReset::where('email' , $request->email)->where('token' , $request->token)->first();
        if (!$passwordReset)
            return response()->json(['status' => 0, 'msg' => 'no such email or token', 'data' => null]);
        $user = Resturant::where('email', $request->email)->first();
        if (!$user)
            return response()->json(['status' => 0, 'msg' => 'this restaurant does not exist', 'data' => null]);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        Mail::send('emails.password-changed', ['data' => 'password is changed'], function ($message) use ($user) {
            $message->from('areejibrahim222@gmail.com');
            $message->to($user->email, 'sofraa')->subject('done');
        });
        return response()->json([
            'status' => 1,
            'msg' => 'restaurant password changed',
            'data' => $user
        ]);


    }
}
