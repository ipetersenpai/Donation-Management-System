<?php

namespace App\Http\Controllers\SMTP;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Invalid verification link.']);
        }

        if (!hash_equals((string) $id, (string) $user->getKey())) {
            abort(403);
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('message', 'Email already verified.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        $user->verified_status = true;
        $user->email_verified_at = now();
        $user->save();

        return view('smtp_templates.email_verified');
    }
}
