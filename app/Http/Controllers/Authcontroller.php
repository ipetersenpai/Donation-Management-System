<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\CustomVerificationMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // Displays the login form to the user.
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handles the login process. It first checks if the credentials match and if the user's email is verified.
    // It also ensures that only users with an 'Admin' role can proceed further.
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // If the user's email is not verified, it sends a verification email and prevents login.
            if (!$user->verified_status) {
                Mail::to($user->email)->send(new CustomVerificationMail($user));

                return back()->withErrors([
                    'email' => 'Email is not verified. We have sent you a link on your email to verify your account.',
                ]);
            }

            // If the user is not an Admin, they are logged out and given a message.
            if (!$user->hasRole('Admin')) {
                Auth::logout();

                return back()->withErrors([
                    'email' => "You don't have permission to continue. To donate, please use the app and log in there.",
                ]);
            }

            // Regenerates session ID to prevent session fixation attacks and updates session timeout.
            $request->session()->regenerate();
            $user->updateSessionTimeout();

            // Redirects to the intended URL or dashboard after successful login.
            return redirect()->intended('dashboard');
        }

        // If the login attempt fails, an error message is returned.
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Shows the registration form to the user.
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Handles the registration process by validating the input data and creating a new user.
    public function register(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'suffix' => 'nullable|string|max:255',
        'birth_date' => 'nullable|date',
        'contact_no' => 'nullable|string|max:255',
        'home_address' => 'nullable|string|max:255',
        'gender' => 'nullable|string|max:6',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/^(?=.*[A-Z])(?=.*\d).+$/' // Enforce at least one uppercase letter and one number
        ],
    ], [
        'password.regex' => 'The password must contain at least one uppercase letter and one number.',
    ]);

    // Creates a new user after hashing the password for security.
    $user = User::create([
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name,
        'last_name' => $request->last_name,
        'suffix' => $request->suffix,
        'birth_date' => $request->birth_date,
        'contact_no' => $request->contact_no,
        'home_address' => $request->home_address,
        'gender' => $request->gender,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    // If user creation is successful, a verification email is sent, and the user is redirected to the login page.
    if ($user) {
        Mail::to($user->email)->send(new CustomVerificationMail($user));
        return redirect()->route('login')->with('success', 'Registration successful. Please check your email for verification.');
    } else {
        // In case of registration failure, return the form with input data and error messages.
        return back()
            ->withInput()
            ->withErrors(['registration_failed' => 'Registration failed. Please try again.']);
    }
}


    // This method verifies the email using a custom verification link. It ensures the link is valid and the email hasn't already been verified.
    public function verifyEmail(EmailVerificationRequest $request)
    {
        // Finds the user by ID from the verification link.
        $user = User::find($request->route('id'));

        // If the user doesn't exist, return an error.
        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Invalid verification link.']);
        }

        // Validates the hash in the verification link.
        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Invalid verification link.']);
        }

        // If the email is already verified, no further action is needed.
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('message', 'Email already verified.');
        }

        // Marks the email as verified, updates the timestamp, and saves the user record.
        $user->markEmailAsVerified();
        $user->verified_status = true;
        $user->email_verified_at = now();
        $user->save();

        // Redirects to a confirmation view indicating successful verification.
        return view('smtp_templates.email_verified');
    }

    // Logs the user out, invalidates the session, and regenerates the CSRF token to prevent security issues.
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
