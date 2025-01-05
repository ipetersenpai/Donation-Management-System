<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Mail\DonationReceipt;
use Illuminate\Support\Facades\Mail;

class DonateController extends Controller
{
    // Store a new donation record in the database and send a receipt email to the user.
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $validated = $request->validate([
            'category_id' => 'required|exists:donation_categories,id', // Ensure the category exists
            'payment_option' => 'required|string|max:50', // Validate payment option as string
            'amount' => 'required|numeric|min:0', // Ensure amount is a positive number
            'reference_no' => 'required|string|unique:donations,reference_no', // Ensure reference number is unique
        ]);

        // Create a new donation entry with the validated data.
        $donation = Donation::create([
            'user_id' => Auth::id(), // Authenticated user's ID
            'category_id' => $validated['category_id'],
            'payment_option' => $validated['payment_option'],
            'amount' => $validated['amount'],
            'reference_no' => $validated['reference_no'],
        ]);

        // Send an email receipt to the user.
        Mail::to(Auth::user()->email)->send(new DonationReceipt($donation));

        // Return a JSON response with a success message and the donation details.
        return response()->json([
            'message' => 'Donation recorded successfully',
            'donation' => $donation,
        ], 201);
    }

    // Retrieve the authenticated user's donation history.
    public function getUserDonations(Request $request)
    {
        // Get the currently authenticated user's ID.
        $userId = Auth::id();

        // Fetch all donations made by the user, including the associated category, and sort by the most recent.
        $donations = Donation::with('category')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get(['payment_option', 'amount', 'created_at', 'reference_no', 'category_id']); // Retrieve only necessary fields.

        // Map the donations into a structured array with relevant details.
        $donationsDetails = $donations->map(function ($donation) {
            return [
                'category_name' => $donation->category->category_name, // Get the category name of the donation.
                'payment_option' => $donation->payment_option, // Payment method used.
                'amount' => $donation->amount, // Amount donated.
                'reference_no' => $donation->reference_no, // Unique reference number.
                'created_at' => $donation->created_at->toDateTimeString(), // Date and time when the donation was made.
            ];
        });

        // Return the donation details as a JSON response.
        return response()->json($donationsDetails);
    }
}

