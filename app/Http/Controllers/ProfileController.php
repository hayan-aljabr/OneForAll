<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;

//use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function change_password(Request $request){
        $validator = Validator::make($request->all(),[
            'old_password'    => 'required',
            'password' => 'min:8|required|max:100',
            'password_confirmation' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'=>'Validation fails',
                'errors'=>$validator->errors()
            ],422);
        }

        $user = $request->user();
        if(Hash::check($request->old_password,$user->password)){
            $user->update([
                'password'=>Hash::make($request->password),
            ]);
            return response()->json([
                'message'=>'Password successfully updated',
            ],200);
        }
        else{
            return response()->json([
                'message'=>'Old password does not match'
            ],400);
        }

    }


    public function update_profile(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'nullable|min:2|max:100',
            'baio' => 'nullable|max:100',
            'email'=>'nullable|',
            'profile_photo' => 'nullable|image|mimes:jpg,bmp,png',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message'=>'Validation fails',
                'errors'=>$validator->errors()
            ],422);
        }

        $user=$request->user();

        if($request->hasFile('profile_photo')){
            if($user->profile_photo){
                $old_path=public_path().'/uploads/profile_images/'
                        .$user->profile_photo;
                if(File::exists($old_path)){
                    File::delete($old_path);
                }
            }
            if($request->hasFile('profile_photo')){
            $profile_photo = 'profile_photo'.time().'.'.$request->profile_photo->extension();
            $request->profile_photo->move(public_path('uploads/profile_images'),$profile_photo);
            }


        }
        else{
            $profile_photo=$user->profile_photo;
        }
        if($request->email){
            $email = $request->email;
        }
        else{
            $email = $user->email;
        }
        if($request->name){
            $name = $request->name;
        }
        else{
            $name = $user->name;
        }

        $user->update([
            'name'=>$name,
            'email' => $email,
            'baio'=>$request->baio,
            'profile_photo'=>$profile_photo,
            'phone_number'=>$request->phone_number,
        ]);

        return response()->json([
            'message'=>'Profile has been updated!',

        ],200);




    }
}
