<?php

namespace App\Http\Controllers\API;

use App\Models\Client;
use App\Models\Offer;
use App\Models\Resturant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Notifications\Notifiable;

class ClientController extends Controller
{

    public function login(Request $request)
    {
//        ,'unique:clients'
        $validator = validator()->make($request->all(), ['email' => ['required', 'email'], 'password' => 'required']);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validation fails', 'data' => $validator->errors()->all()]);
        } else {
            if (count(Client::whereEmail($request->email)) > 0) {
                $client = Client::whereEmail($request->email)->first();
                if (Hash::check($request->password, $client->password)) {
                    return response()->json([
                        'status' => 1,
                        'msg' => 'done',
                        'data' => $client
                    ]);
                } else {
                    return response()->json([
                        'status' => 0,
                        'msg' => 'vot valid password',
                    ]);
                }
            } else {
                return response()->json(['status' => 0, 'msg' => 'this email does not exist', 'data' => null]);
            }
        }

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'string', 'unique:clients'],
            'phone' => ['required', 'string'],
            'city' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'home_discribtion' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validations fails', 'data' => $validator->errors()->all()]);
        } else {
            $request->merge(['api_token' => str_random(60), 'password' => bcrypt($request->password)]);
            $client = Client::create($request->all());
            return response()->json([
                'status' => 1,
                'msg' => 'done',
                'data' => $client
            ]);
        }
    }

    public function getAllRestaurant(Request $request)
    {
        $restaurant = Resturant::where(function ($q) use ($request) {
            if ($request->has('city')) {
                $q->where('city', $request->city);
            }
        })->where('status', 1)->paginate(6);
        if (count($restaurant) > 0) {
            return response()->json(['status' => 1, 'msg' => 'done', 'data' => $restaurant]);
        } else {
            return response()->json(['status' => 0, 'msg' => 'no restaurants', 'data' => null]);
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

    public function showRestaurant(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'id' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validation fails', 'data' => $validator->errors()->all()]);
        }
        $restaurant = Resturant::find($request->id);
        if (!$restaurant) {
            return response()->json(['status' => 0, 'msg' => 'no such restaurant.', 'data' => null]);
        }
        $data = [
            'name' => $restaurant->name,
            'status' => $restaurant->status ,
            'minimum' =>$restaurant->minimum ,
            'logo'  =>$restaurant->photo ,
            'rate' => $restaurant->rate,
            'delivery_cost' => $restaurant->delivery_cost ,
            'categories' => $restaurant->categories()->get() ,
            'products' => $restaurant->products()->get()
        ];
        return response() ->json(['status' => 1 , 'msg' => 'done' , 'data' =>$data]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
