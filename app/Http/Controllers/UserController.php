<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(25);
        return view('pages.users', compact('users'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $searchTerms = explode(' ', $search);

        $users = User::where(function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query
                    ->where('first_name', 'like', "%$term%")
                    ->orWhere('middle_name', 'like', "%$term%")
                    ->orWhere('last_name', 'like', "%$term%");
            }
        })->paginate(10);

        return view('pages.users', compact('users'));
    }
    // Create a User
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'suffix' => 'nullable|string|max:50',
            'birth_date' => 'required|date',
            'contact_no' => 'required|string|max:11',
            'home_address' => 'required|string|max:255',
            'gender' => 'required|string|max:6',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|string|max:20',
            'verified_status' => 'required|boolean',
            'password' => 'required|string|min:8',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);

        if ($validatedData['verified_status']) {
            $validatedData['email_verified_at'] = now();
        }

        User::create($validatedData);

        return redirect()->back()->with('success', 'User Created successfully');
    }

    // Update a user
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'suffix' => 'nullable|string|max:50',
            'birth_date' => 'required|date',
            'contact_no' => 'required|string|max:11',
            'home_address' => 'required|string|max:255',
            'gender' => 'required|string|max:6',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string|max:20',
            'verified_status' => 'required|boolean',
            'password' => 'nullable|string|min:8', // Password is optional
        ]);

        // Hash password if provided
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        // Set email_verified_at timestamp if verified_status is updated to true
        if ($validatedData['verified_status'] && !$user->verified_status) {
            $validatedData['email_verified_at'] = now();
        } elseif (!$validatedData['verified_status'] && $user->verified_status) {
            $validatedData['email_verified_at'] = now();
        }

        if ($user->update($validatedData)) {
            return redirect()->back()->with('success', 'User updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update user');
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Failed to delete user');
        }
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'suffix' => 'nullable|string|max:50',
            'birth_date' => 'required|date',
            'contact_no' => 'required|string|max:11',
            'home_address' => 'required|string|max:255',
            'gender' => 'required|string|max:6',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // Update the user data
        if ($user->update($validatedData)) {
            return redirect()->back()->with('success', 'Profile updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update profile');
        }
    }

    public function changePassword(Request $request)
    {
        // Validate request inputs
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        // Update the user's password
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);

        if ($user->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update password. Please try again.'], 500);
        }
    }

    public function countUsers()
    {
        $totalUsers = User::count();
        return response()->json(['total_users' => $totalUsers]);
    }
}
