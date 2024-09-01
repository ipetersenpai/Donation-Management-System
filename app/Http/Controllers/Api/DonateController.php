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
    public function store(Request $request)
{
    // Validate the request data
    $validated = $request->validate([
        'category_id' => 'required|exists:donation_categories,id',
        'payment_option' => 'required|string|max:50',
        'amount' => 'required|numeric|min:0',
        'reference_no' => 'required|string|unique:donations,reference_no',
    ]);

    // Create a new donation record
    $donation = Donation::create([
        'user_id' => Auth::id(), // Assuming the user is authenticated
        'category_id' => $validated['category_id'],
        'payment_option' => $validated['payment_option'],
        'amount' => $validated['amount'],
        'reference_no' => $validated['reference_no'],
    ]);

    // Send email receipt
    Mail::to(Auth::user()->email)->send(new DonationReceipt($donation));

    // Return a response
    return response()->json([
        'message' => 'Donation recorded successfully',
        'donation' => $donation,
    ], 201);
}

public function getUserDonations(Request $request)
{
    $userId = Auth::id();

    $donations = Donation::with('category')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get(['payment_option', 'amount', 'created_at', 'reference_no', 'category_id']);

    $donationsDetails = $donations->map(function ($donation) {
        return [
            'category_name' => $donation->category->category_name, // Use $donation instead of $donations
            'payment_option' => $donation->payment_option,
            'amount' => $donation->amount,
            'reference_no' => $donation->reference_no,
            'created_at' => $donation->created_at->toDateTimeString(),
        ];
    });

    return response()->json($donationsDetails);
}

}
