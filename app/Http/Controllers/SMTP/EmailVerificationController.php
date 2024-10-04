<?php

namespace App\Http\Controllers\SMTP;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;

class EmailVerificationController extends Controller
{
    // Method to handle the email verification process
    public function verify(Request $request, $id, $hash)
    {
        // Find the user by ID
        $user = User::find($id);

        // If the user is not found, return an error message and redirect to the login page
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid verification link.']);
        }

        // Check if the user ID in the URL matches the authenticated user's ID
        if (!hash_equals((string) $id, (string) $user->getKey())) {
            abort(403); // Return a 403 Forbidden status if the IDs do not match
        }

        // Check if the hash from the URL matches the hash of the user's email
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403); // Return a 403 Forbidden status if the hashes do not match
        }

        // If the user has already verified their email, redirect with a message
        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('message', 'Email already verified.');
        }

        // Mark the user's email as verified and trigger the Verified event
        if ($user->markEmailAsVerified()) {
            event(new Verified($user)); // Trigger the verified event
        }

        // Set the user's `verified_status` to true and save the verification timestamp
        $user->verified_status = true;
        $user->email_verified_at = now();
        $user->save();

        // Display the email verification success view
        return view('smtp_templates.email_verified');
    }
}
