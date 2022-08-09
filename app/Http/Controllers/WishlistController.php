<?php

namespace App\Http\Controllers;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        $this->middleware(['auth:api']);
    }
    public function index()
    {

        $user = Auth::user();
        $wishlists = Wishlist::with(['product']);
        return $wishlists->where("user_id", "=", $user->id)->orderby('id', 'desc')->get();
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, array(

            'product_id' =>'required',
          ));

        $status = Wishlist::where('user_id',$user->id )
                            ->where('product_id',$request->product_id)
                            ->first();
        if(isset($status->user_id) and isset($request->product_id)){
            return response()->json(['message'=>'You Already have this product'],403);

        }
        else{
            $wishlist = new Wishlist;
            $wishlist->user_id = $user->id;
            $wishlist->product_id = $request->product_id;
            $wishlist->save();

            return response()->json(['message'=>'Product Added!','data'=>$wishlist],200);
        }




    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    {   $user = Auth::user();

        $wishlist = Wishlist::find($id);



        if($wishlist->user_id == $user->id){
            $wishlist->delete();
            return response()->json(['message'=>'Done','data'=>$this->index()],200);
        }
       else{
        return 'This product is not yours to delete';
       }





       /* $user = Auth::user();
        $wishlist = Wishlist::where('user_id',$user->id)
                            ->where('product_id', $id);
        if($wishlist){
            $wishlist->delete();
            return "done";
        }
        else{
            return "not done";
        }*/


                            /* if(isset($user->id) and isset($id)){
            $wishlist->delete();
            return response()->json(['message'=>'product deleted'],200);
        }
        else{
            return "error";
        }*/



    }
}
