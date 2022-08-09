<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
      //  $this->middleware('access.controll');
    }

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
    public function destroy(User $user)
    {
        if($user->user_type == 'USR'){
            $user->delete();
        }
        else
            return response()->json([
                'message'=>'you are not allowed to delete admins or super admins'
            ],403) ;

  }
  public function deleteProduct(Product $product){
    $product->delete();

  }
  public function adminSeeProducts(){
    return Product::all();
  }
  public function adminSeeUsers(){
    return User::all();
  }
  public function adminLastProducts(){
    return Product::latest()->take(5)->get();
  }
  public function adminLastUsers(){
    return User::latest()->take(5)->get();
  }


}
