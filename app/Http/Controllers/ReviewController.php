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
    public function index(Product $product)
    {
        return ReviewResource::collection($product->reviews);
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
        $product->reviews();
        $findReview = Review::where(['user_id' => Auth::user()->id, 'product_id' => $request->product_id])->first();
       if($findReview) {
            return "error', 'You already reviewed this product";
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
    public function show(Review $review)
    {
        return $review;

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
