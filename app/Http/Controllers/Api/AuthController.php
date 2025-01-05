<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    // Endpoint to get user details
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

    // Endpoint to update user details
    public function updateUserDetails(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'birth_date' => 'required|date',
            'contact_no' => 'required|string|max:15',
            'home_address' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other',
        ]);

        $user->update($request->only([
            'first_name',
            'middle_name',
            'last_name',
            'suffix',
            'birth_date',
            'contact_no',
            'home_address',
            'gender'
        ]));

        return response()->json(['message' => 'User details updated successfully']);
    }

    // Endpoint to update password
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        // Check if the current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }

    // Endpoint for Logout
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete(); // Revoke all tokens for the user

        return response()->json(['message' => 'Logged out successfully']);
    }
}
