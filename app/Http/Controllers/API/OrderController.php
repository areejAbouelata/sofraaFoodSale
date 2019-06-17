<?php

namespace App\Http\Controllers\API;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\Resturant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'resturant_id' => 'required|numeric|exists:resturants,id',
            'payment_type' => ['required', Rule::in(['cache', 'creadit'])],
            'delivary_address' => 'required|string',
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'numeric',
            'amount' => 'required|array',
            'amount.*' => 'numeric',
            'note' => 'array',
            'note*' => 'string'
        ]);
//        regard status binding total on restaurant product price order id
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validation error', 'data' => $validator->errors()->all()]);
        }
//        $restaurant = Resturant::where('id' , $request->resturant_id)->first();
        $request->merge(['status' => 'binding' ]);
        $order = $request->user()->orders()->create($request->only(['resturant_id', 'payment_type', 'delivary_address', 'status' => 'binding']));
//        send notification with this action here
//        ---------------------------------------  
//        -----------------add order products-----------------------------
        for ($i = 0; $i < count($request->input('product_id')); $i++)
            $products_order = $order->products()->attach($request->product_id[$i], ['amount' => $m = $request->amount[$i] != null ? $request->amount[$i] : 1, 'product_price' => Product::whereId($request->product_id[$i])->first()->price, 'note' => $n = $request->note[$i] != null ? $request->note[$i] : ' ']);
//        -------------------------order total computations------------------------------------------
        $order_details = OrderProduct::where('order_id', $order->id)->get();
        $total = 0;
        for ($i = 0; $i < count($order_details); $i++)
            $total += ($order_details[$i]->amount * $order_details[$i]->product_price);
//        warning
        $delivery_cost= $order->restaurant->delivery_cost;
        $order->update(['total' => $total,'delivery_cost' => $delivery_cost]);
//        --------------------------------------------------------------------------------------------
        return response()->json(['status' => 1, 'msg' => 'order is added', 'data' => $order->fresh()]);
    }

    public function clientInPastOrders(Request $request)
    {
        $orders = $request->user()->orders()->where('status', 'binding')->orWhere('status', 'accepted')->paginate(6);
        if (count($orders) == 0)
            return response()->json(['status' => 0, 'msg' => 'no last orders', 'data' => null]);
        return response()->json(['status' => 1, 'msg' => 'in past orders', 'data' => $orders]);
    }

    public function clientCurrentOrders(Request $request)
    {
        $orders = $request->user()->orders()->where('status', 'delivered')->orWhere('status', 'rejected')->paginate(6);
        if (count($orders) == 0)
            return response()->json(['status' => 0, 'msg' => 'no orders', 'data' => null]);
        return response()->json(['status' => 1, 'msg' => 'current orders done', 'data' => $orders]);
    }

    public function clientAcceptOrder(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'order_id' => ['required', 'exists:orders,id'],
            'status' => ['required', Rule::in(['delivered'])]
        ]);
        if ($validator->fails())
            return response()->json(['status' => 0, 'msg' => 'validation fails', 'data' => $validator->errors()->all()]);
        $order = $request->user()->orders()->where('id', $request->order_id)->first();
        if ($order == null) {
            return response()->json(['status' => 0, 'msg' => 'order does not exist', 'data' => null]);
        } elseif ($order->status == 'accepted' || $order->status == 'binding') {
            $order->update(['status' => $request->status]);
            return response()->json(['status' => 1, 'msg' => 'order is delivered', 'data' => $order]);
        } else {
            return response()->json(['status' => 0, 'msg' => 'order state can not be changed', 'data' => $order]);
        }
    }

    public function clientRejectOrder(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'order_id' => ['required', 'numeric', 'exists:orders,id'],
            'status' => ['required', Rule::in(['rejected'])]
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validation fails', 'data' => $validator->errors()->all()]);
        }
        $order = $request->user()->orders()->where('id', $request->order_id)->first();
        if ($order == null) {
            return response()->json(['status' => 0, 'msg' => 'there is no order', 'data' => null]);
        } elseif ($order->status == 'binding') {
            $order->update(['status' => 'rejected']);
            return response()->json(['status' => 1, 'msg' => 'order is rejected by client', 'data' => $order]);
        } else {
            return response()->json(['status' => 0, 'msg' => 'can not reject the order', 'data' => $order]);
        }
    }

    public function getRestaurantOrders(Request $request)
    {
//        all restaurants orders here
    }

    public function restaurantNewOrders(Request $request)
    {
        $orders = $request->user()->orders()->where('status', 'binding')->paginate(6);
        if (count($orders) == 0 || $orders->first() == null) {
            return response()->json(['status' => 0, 'msg' => 'zero orders', 'data' => null]);
        }
        return response()->json(['status' => 1, 'msg' => 'restaurant orders', 'data' => $orders]);
    }

    public function restaurantAcceptOrder(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'order_id' => 'required|numeric|exists:orders,id',
            'status' => ['required', Rule::in(['accepted'])]
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validation error', 'data' => $validator->errors()->all()]);
        }
        $order = $request->user()->orders()->where('id', $request->order_id)->first();
        if (!$order) {
            return response()->json(['status' => 0, 'msg' => 'this order not exist', 'data' => null]);
        }
        if ($order->status == 'binding') {
            $order->status = $request->status;
            $order->save();
            return response()->json(['status' => 1, 'msg' => 'order accepted', 'data' => $order->fresh()]);
        }
        return response()->json(['status' => 0, 'msg' => 'order status can not be updated it is not binding', 'data' => $order]);
    }

    public function restaurantRejectOrder(Request $request)
    {
        $validator = validator()->make($request->all(), ['order_id' => 'required|numeric|exists:orders,id',
            'status' => ['required', Rule::in(['rejected'])]
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validation error', 'data' => $validator->errors()->all()]);
        }
        $order = $request->user()->orders()->where('id', $request->order_id)->first();
        if (!$order) {
            return response()->json(['status' => 0, 'msg' => 'no such order', 'data' => null]);
        }
        if ($order->status == 'binding' || $order->status == 'accepted') {
            $order->status = $request->status;
            $order->save();
            return response()->json(['status' => 1, 'msg' => 'order updated', 'data' => $order->fresh()]);
        }
        return response()->json(['status' => 0, 'msg' => 'order status can not be updated', 'data' => $order]);
    }

    public function restaurantCurrentOrders(Request $request)
    {
        $orders = $request->user()->orders()->where('status', 'accepted')->paginate(6);
        if (count($orders) == 0 || $orders->first() == null) {
            return response()->json(['status' => 0, 'msg' => 'no current orders', 'data' => null]);
        }
        return response()->json(['status' => 1, 'msg' => 'done', 'data' => $orders]);
    }

    public function restaurantDeliverOrder(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'order_id' => 'required|numeric|exists:orders,id',
            'status' => ['required', Rule::in('delivered')]
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'msg' => 'validation error', 'data' => $validator->errors()->all()]);
        }
        $order = $request->user()->orders()->where('id', $request->order_id)->first();
        if (!$order) {
            return response()->json(['status' => 0, 'msg' => 'this order does not exist', 'data' => null]);
        }
        if ($order->status == 'accepted')
            $order->status = $request->status;
        $order->save();
        return response()->json(['status' => 1, 'msg' => 'order is delivered', 'data' => $order->fresh()]);

        return response()->json(['status' => 0, 'msg' => 'order can not be updated as it was not accepted', 'data' => $order]);
    }

    public function restaurantDeliveredOrders(Request $request)
    {
        $orders = $request->user()->orders()->where('status', 'delivered')->paginate(6);
        if ($orders->first() == null || count($orders) == 0) {
            return response()->json(['status' => 0 , 'msg' => 'no delivered orders', 'data' => null]);
        }
        return response()->json(['status' => 1 , 'msg' => 'delivered orders' , 'data' => $orders]);
    }

}
