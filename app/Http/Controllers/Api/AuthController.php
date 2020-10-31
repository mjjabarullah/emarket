<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'phone' => 'required|string|unique:users|max:14',
            'address' => 'required|string|max:225',
            'store_name' => 'nullable',
            'password' => 'required|string|confirmed|min:8'
        ]);

        if ($this->isAdminRegistered()) {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'store_name' => $request->store_name,
                'password' => bcrypt($request->password)
            ]);
        } else {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'account_type' => 'admin',
                'store_name' => $request->store_name,
                'password' => bcrypt($request->password)
            ]);
        }

        $user->save();

        return response()->json([
            'message' => 'Account created Successfully!',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Email or Password wrong.'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Access Token');
        $user->access_token = $tokenResult->accessToken;

        return response()->json([
            'message' => 'Logged in successfully!',
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function isAdminRegistered()
    {
        $users = User::all()->count();
        return $users > 0;
    }

}
