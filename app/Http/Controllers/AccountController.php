<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware(['auth:api'])->except('adminMoney');
    }
    public function index()
    {

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
        $user = 1;


        $mep = Account::where('user_id',Auth::user()->id)->first();
        $another = Account::where('user_id',$user)->first();


        if($request->send){
            if($mep->balance >= $request->send){
                $mep->update([
                    'balance'=>  ($mep->balance) - ($request->send)
                ]);
                $another->update([
                    'balance'=>($another->balance) + ($request->send),
                ]);
                return $mep;

            }
            else{
                return response()->json([
                    'message'=>'YOU DONT HAVE ENOUGH POINTS'
                ],403);
            }





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
