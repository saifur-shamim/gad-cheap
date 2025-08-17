<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Log;

class AuthController extends Controller
{

    // POST /api/register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'phone'      => 'required|string|max:20|unique:users,phone',
            'password'   => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->password   = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status'  => 201,
            'message' => 'User registered successfully',
        ], 201);
    }

    // POST /api/login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors(),
            ], 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $token = $user->createToken('api')->plainTextToken;

            return response()->json([
                'status'  => 200,
                'message' => 'Logged in successfully',
                'user'    => $user,
                'token'   => $token,
                'token_type' => 'Bearer',
            ], 200);
        }

        return response()->json([
            'status'  => 401,
            'message' => 'Either email or password is incorrect.',
        ], 401);
    }

    // POST /api/logout  
    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out.'], 200);
    }
}
