<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \App\Models\UserHistory;

class UserController extends Controller
{
    public function index()
{
    $users = User::paginate(25);
    $userHistory = UserHistory::all();

    return view('pages.users', compact('users', 'userHistory'));
}


public function search(Request $request)
{
    $search = $request->input('search');

    $users = User::query();

    // Handle "Verified" and "Not Verified" specifically
    if (strtolower($search) === 'verified') {
        $users = $users->where('verified_status', true);
    } elseif (strtolower($search) === 'not verified') {
        $users = $users->where('verified_status', false);
    } else {
        // General search for names if the search term does not match "Verified" or "Not Verified"
        $users = $users->where(function ($query) use ($search) {
            $query->where('first_name', 'like', "%$search%")
                  ->orWhere('middle_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('role', 'like', "%$search%");
        });
    }

    $users = $users->paginate(10);
    $userHistory = UserHistory::all();

    return view('pages.users', compact('users', 'userHistory'));
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
        'email' => 'required|email|unique:users,email,' . $user->id, // Ensures the email is unique, excluding the current user's email
        'role' => 'required|string|max:20',
        'verified_status' => 'required|boolean',
        'password' => 'nullable|string|min:8',
    ]);

    // Check if the email already exists (other than the user's own email)
    $existingUser = User::where('email', $validatedData['email'])->where('id', '!=', $user->id)->first();
    if ($existingUser) {
        return redirect()->back()->with('error', 'The email address is already taken by another user.');
    }

    // Save old data to user_history table
    UserHistory::create([
        'user_id' => $user->id,
        'first_name' => $user->first_name,
        'middle_name' => $user->middle_name,
        'last_name' => $user->last_name,
        'role' => $user->role,
        'suffix' => $user->suffix,
        'birth_date' => $user->birth_date,
        'contact_no' => $user->contact_no,
        'home_address' => $user->home_address,
        'gender' => $user->gender,
        'email' => $user->email,
        'verified_status' => $user->verified_status,
        'action' => 'updated',
        'action_at' => now(),
    ]);

    // Hash password if provided
    if (!empty($validatedData['password'])) {
        $validatedData['password'] = bcrypt($validatedData['password']);
    } else {
        unset($validatedData['password']);
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
        // Save user data to user_history table
        UserHistory::create([
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'role' => $user->role,
            'suffix' => $user->suffix,
            'birth_date' => $user->birth_date,
            'contact_no' => $user->contact_no,
            'home_address' => $user->home_address,
            'gender' => $user->gender,
            'email' => $user->email,
            'verified_status' => $user->verified_status,
            'action' => 'deleted',
            'action_at' => now(),
        ]);

        // Delete the user
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
        return redirect()->back()->with('success', 'Password updated successfully!');
    } else {
        return redirect()->back()->with('error', 'Failed to update password. Please try again.');
    }
}


    public function countUsers()
    {
        $totalUsers = User::count();
        return response()->json(['total_users' => $totalUsers]);
    }
}
