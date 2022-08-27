<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Product;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Models\Role;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(['auth:api'])->except('adminMoney','index');
    }
    public function index()
    {
        return Transaction::with('product','account.user')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\response
     */
    public function create()
    {
        $account = Account::create([
            'user_id'=>Auth::user(),
        ]);

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

        $status = Account::where('user_id',$user->id )
                ->first();
        if(isset($status->user_id)){
        return response()->json(['message'=>'You Already have an Account'],403);
        }

      else{
             $account =  Account::create([
        'user_id'=>$user->id
    ]);
    $account->load('user:id,name,email');
    $account->save();
    return response()->json(['message'=>'Account created','data'=>$account]);
      }





    }
    public function send(Request $request)
    {
        $product_id = $request->product_id;
        $user = 1;


        $mep = Account::where('user_id',Auth::user()->id)->first();
        $another = Account::where('user_id',$user)->first();
        $product = Product::where('id',$product_id)->first();





            if($product->quantity >=0 and $mep->balance >= $product->price){

                $mep->update([
                    'balance'=>  ($mep->balance) - ($product->price)
                ]);
                $another->update([
                    'balance'=>($another->balance) + ($product->price),
                ]);
                $trans = Transaction::create([
                    'account_id'=> $mep->id,
                    'product_id'=>$request->product_id,
                    'operation'=>$product->price
                ]);

                return $trans;
            }

            else{
                return response()->json([
                    'message'=>'YOU DONT HAVE ENOUGH POINTS'
                ],403);
            }









    }

    public function adminMoney(Request $request){
        $another = Account::where('user_id',$request->user)->first();


        if($request->send){

            $another->update([
                'balance'=>($another->balance) + ($request->send),
            ]);
            return $another;




        }
        else{
            return response()->json(['message'=>'you dont have an account']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(){
        $user = Account::with('user')->where('user_id',Auth::user()->id)->first();
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Account::with('user')->where('id',$id)->first();
     /**/
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
