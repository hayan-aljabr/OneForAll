<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

use App\Models\CartItmes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [ 'cartKey' => 'required', ]);
        if ($validator->fails()) { return response()->json([ 'errors' => $validator->errors(), ], 422); }

        $cart = Cart::where('key',$request->cartKey)->first();
        if ( $cart){  return response()->json([ 'cart' => $cart->id, 'items' => $this->getCartItems($cart->id)->toArray(), ], 200);};

        return response()->json([ 'message' => 'The CarKey you provided does not match the Cart Key for this Cart.', ], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cart = Cart::create([
            'key' => md5(uniqid(rand(), true)),
            'user_id' => (Auth::user())->id,
        ]);

        return response()->json([
            'Message' => 'A new cart have been created for you!',
            'cartId' => $cart->id,
            'cartKey' => $cart->key,
        ]);


    }
    public function addProducts(Request $request){
       // return "dd";

        $validator = Validator::make($request->all(), [
            'cartKey' => 'required',
            'quantity' => 'required|numeric|min:1|max:10',
        ]);
        if ($validator->fails()) { return response()->json([ 'errors' => $validator->errors(),  ], 400); }

        //Check if the Cart exist or return 404 not found.
        $cart = Cart::where('key',$request->cartKey)->first();
        if ( $cart):
            //Check if the proudct exist or return 404 not found.
            $products = Product::find($request->product_id);
            if($products):
                //check if the the same product is already in the Cart, if true update the quantity, if not create a new one.
                $cartItem = CartItmes::where(['cart_id' => $cart->id, 'product_id' => $products->id])->first();
                if ($cartItem):
                    CartItmes::where(['cart_id' => $cart->id, 'product_id' => $products->id])->update(['quantity' => $request->quantity]);
                else:
                    CartItmes::create(['cart_id' => $cart->id, 'product_id' => $products->id, 'quantity' => $request->quantity]);
                endif;
                return response()->json(['message' => 'The Cart was updated with the given product information successfully'], 200);
            else:
                return response()->json([ 'message' => 'The Product you\'re trying to add does not exist.', ], 404);
            endif;
        else:
            return response()->json([ 'message' => 'The CarKey you provided does not match the Cart Key for this Cart.', ], 404);
        endif;
    }

    public function getCartItems($cart_id)
    {
       // return "dd";

        $items = CartItmes:: with(['Product' => function ($query) {
            $query->select('id',  'name',
            'price',
            'description',
            'image_url',
            'quantity',
            'category_id',);
            $query->with(['Category' => function ($qu) {
                $qu->select('id', 'name');
            }]);
        }]) ->where('cart_id',$cart_id)->get();
        return $items;
    }

    public function Order(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'cartKey' => 'required',
        ]);
        if  ($validator->fails()) { return response()->json([ 'errors' => $validator->errors(),  ], 400); }
        //return "dd";

        $cart = Cart::where('key',$request->cartKey)->first();
        if ($cart):
            $items = $this->getCartItems($cart->id);
            $order =  $products =  $price = [];
            foreach ($items as $key => $item):
                if(isset($item->Product) && $item->Product != null):
                    $products[$key] = $item->Product->id;
                    $price[$key] = ($item->Product->price  *  $item->quantity);
                endif;
            endforeach;

             $user = Auth::user();
            $order = Order::create([
                'products'=>json_encode($products),
                'totalPrice'=>array_sum($price),
                'name'=>$user->name,
                'phone'=>$user->phone_number,
                'email'=>$user->email,
                'user_id'=>  (Auth::user())->id,
            ]);

            $cart->delete(); // cart delete
            return response()->json([
                'message' => 'you\'re order has been completed successfully, thanks for shopping with us!',
                'orderID' => $order->id,
            ], 200);
        else:
            return response()->json([ 'message' => 'The CarKey you provided does not match the Cart Key for this Cart.', ], 404);
        endif;
    }
    public function deletefromcart($cart_id){


    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
