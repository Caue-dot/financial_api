<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(RegisterRequest $request){
        User::create($request->validated());
        return response()->json(['message' => 'User successfuly registered'], 201);
    }

    public function login(LoginRequest $request){

        if(!Auth::attempt($request->validated())){
              return response()->json(['message' => 'Incorrect Credentials'], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();

        $token = $user->createToken('user-token', ['*'])->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'user' => $user,
            'token' => $token
        ],   200);

    }


     public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['message'=> 'User logged out'], 200);
    }

}
