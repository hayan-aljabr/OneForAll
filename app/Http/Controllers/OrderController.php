<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
class OrderController extends Controller
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Order $order)
    {






       // $order = Auth::user()->orders()->orderBy('created_at', 'desc')->paginate(6);
        $query = Order::where('id', $request->order_id);
       /* if (Auth::id()) {
            $userID = auth('custom_api')->user()->id;
            $query->where('userID',$userID);
        }*/
        $order =$query->get();
        if ( $order):
            foreach( $order as  $val):
                $product_ids = json_decode($val->products);
                $products = Product::whereIn('id',$product_ids)
                ->select('id', 'name',
                'price',
                'description',
                'image_url',
                'quantity',
                'category_id',)
                ->with(['Category' => function ($qu) {
                    $qu->select('id', 'name');
                }])->get()->toArray();
                $val->products =$products;
            endforeach;

            return response()->json([ 'order' =>  $order->toArray() ], 200);
        else:
            return response()->json([ 'message' => 'The order id you provided does not match the order list.', ], 404);
        endif;





    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
