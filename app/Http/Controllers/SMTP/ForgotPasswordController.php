<?php

namespace App\Http\Controllers\SMTP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    // Display the form to request a password reset link
    public function showLinkRequestForm()
    {
        // Return the 'forgot password' view where users can submit their email for a reset link
        return view('auth.forgot');
    }

    // Handle sending the password reset link to the user's email
    public function sendResetLinkEmail(Request $request)
    {
        // Validate the email field in the incoming request
        $request->validate(['email' => 'required|email']); // Ensure the email is required and properly formatted

        // Attempt to send a reset link to the provided email using Laravel's Password facade
        $status = Password::sendResetLink(
            $request->only('email') // Only extract the email from the request
        );

        // Check if the reset link was successfully sent
        return $status === Password::RESET_LINK_SENT
            // If successful, redirect back with a status message
            ? back()->with(['status' => __($status)])
            // If there was an error, return back with an error message
            : back()->withErrors(['email' => __($status)]);
    }
}
