<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // create Register API @param (name, email, password)
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create($data);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    // create Login API @param (email, password)
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // create token
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json(['message' => 'Login successful', 'token' => $token], 200);
    }

    // create Logout API
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Logout successful'], 200);
    }

    // create User Profile API
    public function profile()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json(['message' => 'User profile data', 'user' => $user], 200);
    }
}
