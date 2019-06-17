<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function addOffer(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'title' => 'required',
            'hint' => 'required',
            'photo' => 'image',
            'date_from' => 'required|date',
            'date_to' => 'required|date'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'failed validation', 'data' => $validator->errors()->all()]);
        } else {
            $offer = $request->user()->offers()->create($request->all());
            if ($request->hasFile('photo')) {
                $base_path = base_path() . '/uploads/offers';
                $photo = $request->photo;
                $extension = $photo->getClientOriginalExtension();
                $rename = time() . rand(0, 22222) . '.' . $extension;
                $photo->move($base_path, $rename);
                $offer->update(['photo' => '/uploads/offers/' . $rename]);
            }
            return response()->json(['status' => 1, 'msg' => 'done', 'data' => $offer]);
        }
    }

    public function allCurrentOffers(Request $request)
    {
        $offers = $request->user()->offers()->where('date_to', '>=', Carbon::today()->toDateTimeString())->paginate(6);
        if ($offers->first() == null || count($offers) == 0) {
            return response()->json(['status' => 0, 'msg' => 'no data', 'data' => null]);
        }
        return response()->json(['status' => 1, 'msg' => 'done', 'data' => $offers]);
    }

    public function allOffers(Request $request)
    {
        $offers = $request->user()->offers()->paginate(6);
        if ($offers->first() == null || count($offers) == 0) {
            return response()->json(['status' => 0, 'msg' => 'no data', 'data' => null]);
        }
        return response()->json(['status' => 1, 'msg' => 'done', 'data' => $offers]);
    }


    public function newOffersForClients()
    {
        $offers = Offer::where('date_to', '>=', Carbon::today()->toDateTimeString())->paginate(6);
        if ($offers->first() == null || count($offers) == 0) {
            return response()->json(['status' => 0, 'msg' => 'no data', 'data' => null]);
        }

        return response()->json(['status' => 1, 'msg' => 'done offers', 'data' => $offers]);

    }


}
