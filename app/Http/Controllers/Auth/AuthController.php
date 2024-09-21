<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //Register New user
    public function register(Request $request){
        $validator=Validator::make($request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|unique:Users,email|email|string',
            'password'=>'required|confirmed|string|min:8'
        ]);
        if ($validator->fails()){
            return ApiResponse::sendResponse(422,'Register Validation Errors',$validator->messages()->all());
        }
        $user=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);
        $data ['token']=$user->createToken('auth_token')->plainTextToken;
        $data['name']=$user->name;
        $data['email']=$user->email;
        return ApiResponse::sendResponse(201,'Registered Successfully',$data);
    }


    //Login user have account
    public function login(Request $request){
        $validator=Validator::make($request->all(),[
            'email'=>'required|email|string',
            'password'=>'required|string'
        ]);
        if ($validator->fails()){
            return ApiResponse::sendResponse(422,'Login Validation Errors',$validator->messages()->all());
        }
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user=Auth::user();
            $data ['token']=$user->createToken('auth_token')->plainTextToken;
            $data['name']=$user->name;
            $data['email']=$user->email;
            return ApiResponse::sendResponse(200,'User Logged Successfully',$data);
        }
        else{
            return ApiResponse::sendResponse(401,'User Credentials does not exist',null);
        }
    }


    //User Logout
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return ApiResponse::sendResponse(200,'Logout Successfully',null);
    }
}
