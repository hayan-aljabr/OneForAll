<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ReviewResource;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api')->except('index','show');
    }
    public function index($product)
    {
        $product = Product::with(['user','reviews.user']);
        return $product->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product)
    {


      /*  $validator = Validator::make($request->all(),[
            'star' => 'required|numeric|min:0|max:5' ,
            'review' =>'required|string',
    ]);
        if ($validator->fails()) {
            return response()->json(['message'=>'Validation errors', 'error' => $validator->messages()],422);
        }*/
       // $product->reviews();
       // $findReview = Review::where(['user_id' => Auth::user()->id, 'product_id' => $request->product_id])->first();
       //if($findReview) {
         //   return "error', 'You already reviewed this product";
       // }
      // $review = Review::where(['user_id'=>auth()->user()->id, 'product_id'=> $product]);
            $user = Auth::user();
            $status = Review::where('user_id',$user->id )
            ->where('product_id',$request->product_id);
                if(isset($status->user_id) and isset($request->product_id)){
                return response()->json(['message'=>'You Already reviewd this product'],403);

             }


        if($request->star > '5'){
            return response()->json([
                'message'=>'5 star or less'
            ],403);

        }
        if($request->star < '1'){
            return response()->json([
                'message'=>'1 star or more'
            ],403);

        }

        else{

                $review = new Review();
                $review->review = $request->review;
                $review->star = $request->star;
                $review->user_id = $request->user_id =auth()->user()->id;
                $product->reviews()->save($review);
                return response()->json(['message'=>'Review Added', 'review'=>$review]);
           /*


        */
        }






        $product->reviews()->save($review);
        return response()->json()([
            'data'=>$review
        ],200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
         $fullReview = $product->with(['reviews']);
         return $fullReview->get();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {

            $request->validate([
                'star' => 'required',
                'review' => 'required'
            ]);

            $review->update([
                'star'=> $request->star,
                'review'=> $request->review,
            ]);
            $review->save();
            return $review;


    }

    /*  if($user->id == $review->user_id){

        }*/

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
