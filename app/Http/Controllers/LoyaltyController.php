<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Loyalty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoyaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct() {
        $this->middleware(['auth:api']);
    }
    public function index(Request $request)
    {

        return Loyalty::all();
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
       $user = Account::with('user')->where('user_id',$request->user_id)->first();
        $loyalty = Loyalty::create([
            'user_id'=>$request->user_id,
            'message'=>'Congratulations for being customer of the mounth, contact this email : admin@oneforall.com'
        ]);
        $user->update([
            'balance'=>  ($user->balance) + 1000
        ]);

        return $loyalty;
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
    public function userLoyal(){
        Auth::user();
        $loyalty = Loyalty::where('user_id',Auth::user()->id)->first();
        if($loyalty){
            return response()->json(['data'=>$loyalty->message],200);
        }
        else{
            return response()->json(['message'=>'welcome']);
        }

    }
    public function userMessage(Request $request){

        $loyalty = Loyalty::create([
            'user_id'=>$request->user_id,
            'message'=>$request->message
        ]);


        return $loyalty;


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
