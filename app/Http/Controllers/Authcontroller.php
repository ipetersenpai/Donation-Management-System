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
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

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
            'password' => 'required|string|min:8|confirmed',
        ]);

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

        if ($user) {
            Mail::to($user->email)->send(new CustomVerificationMail($user));
            return redirect()->route('login')->with('success', 'Registration successful. Please check your email for verification.');
        } else {
            return back()
                ->withInput()
                ->withErrors(['registration_failed' => 'Registration failed. Please try again.']);
        }
    }

    public function verifyEmail(EmailVerificationRequest $request)
    {
        $user = User::find($request->route('id'));

        if (!$user) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Invalid verification link.']);
        }

        if (!hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'Invalid verification link.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('message', 'Email already verified.');
        }

        $user->markEmailAsVerified();
        $user->verified_status = true;
        $user->email_verified_at = now(); // Set the current timestamp
        $user->save();

        return view('smtp_templates.email_verified');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
