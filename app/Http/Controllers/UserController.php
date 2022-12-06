<?php

namespace App\Http\Controllers;

use HasApiTokens;
//use Illuminate\Validation\Validator;
//use Dotenv\Validator;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Account;
use App\Rules\AgeRange;
use Dotenv\Parser\Value;
use Illuminate\Support\Str;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\RefreshToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\AuthenticationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //bilal....................
        return User::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
 
     public function create(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
          //  'user_name' => 'unique:users|required|string|max:255',
            'email'    => 'unique:users|required|string|email|max:255',
            'password' => 'min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:8',
            'phone_number' =>'nullable|min:10|max:10',
            'age'=>['nullable',new AgeRange]

        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()],422);
        }
  
        $request['password']= Hash::make($request['password']);

        $user = User::query()->create([
            'name' => $request->name,
           // 'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone_number' =>$request->phone_number,
            'age' => $request->age,



           // 'remember_token'=>$request->createToken('personal access token')
        ]);

        $token = $user->createToken('personal access token');
        $user->remember_token = $token;

        $data["user"]=$user;

        $data["token_type"] = 'Bearer';
        $data["access_token"] = $token->accessToken;

        //return response()->json($data);

        ;



             $account =  Account::create([
        'user_id'=>$user->id
    ]);
    $account->load('user:id,name,email');
    $account->save();
    return response()->json($data);
    return response()->json(['message'=>'Account created','data'=>$data,'data2'=>$account]);
      }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>['required','string','email','max:255'],
            'password'=>['required','string','min:8']

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'=>'Validation fails',
                'errors'=>$validator->errors()
            ],401);
        }

        $credentials = request(['email','password']);

        if(!Auth::attempt($credentials)){
            throw new AuthenticationException();
        }

        $user = $request->user();

        $token = $user->createToken('personal access token');



        $data["user"]= $user;
        $data["token_type"]='Bearer';
        $data["access_token"]=$token->accessToken;

        return response()->json($data);

    }
    public function logout(Request $request) {

    
        $accessToken = auth()->user()->token();
        $token= $request->user()->tokens->find($accessToken);
        $token->revoke();
        return response(['message' => 'You have been successfully logged out.'], 200);

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { //...................bilal
        return User::find($id);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        $user = Auth::user();
        $user->update([
            'name' => $request->name,
           // 'user_name' => $request->user_name,
            'email' => $request->email,
            'phone_number' =>$request->phone_number,
            'age' => $request->age,
        ]);

         return $user;


     }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {


        if(Auth::id() == $user->id){
            $user->delete();
       }
       else{
           return redirect()->back();
       }
    }
    public function getMostUser(Request $request){
        $user = User::withCount(['products']);
      //  $user->where('created_at',Carbon::now()->month());
        if($request->sortOrder && in_array($request->sortOrder,['asc','desc'])){
            $sortOrder=$request->sortOrder;
        }
        else{
            $sortOrder='desc';
        }
       $result= $user->whereMonth('created_at', '=', date('m'));
       $final = $result->orderBY('products_count',$sortOrder)->take(5)->get();
        return $final;

        
    }

}
