<?php

namespace App\Http\Controllers\API;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class ContactsController extends Controller
{
    public function contacts(Request $request)
    {
        $validator = validator()->make($request->all(),[
            'name' => 'required' ,
            'email' => 'required|email' ,
            'phone' => 'required' ,
            'type' => ['required',
            Rule::in(['complaint' , 'suggetion' ,'query'])
            ] ,
            'discribtion' => 'string'
        ]);
        if ($validator->fails())
            return  response()->json([
                'status' => 0 ,
                'msg' => 'not done' ,
                'data' => $validator->errors()->all()
            ]);
        else
            $contacts  = Contact::create($request->all());
            return response()->json([
                'status' => 1 ,
                'msg' => 'done' ,
                'data' => $contacts
            ]) ;
    }
}
