<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Endpoint for Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function getUsersDetails(Request $request)
    {
        $user = $request->user();

        $userDetails = $user->only([
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'birth_date',
            'contact_no',
            'home_address',
            'gender',
            'email',
            'role'
        ]);

        return response()->json($userDetails);
    }
}
