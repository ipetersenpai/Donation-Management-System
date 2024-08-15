<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;

class DonateController extends Controller
{
    public function store(Request $request)
    {


        // Create donation record
        $donation = new Donation();
        $donation->user_id = Auth::id(); // Get the authenticated user ID
        $donation->category_id = $request->input('category_id');
        $donation->payment_option = $request->input('payment_option');
        $donation->amount = $request->input('amount');
        $donation->save();

        return response()->json(['message' => 'Thank you so much for your generous donation! Your kindness and support mean the world to us and will make a significant impact in the lives of those we help.', 'donation' => $donation], 201);
    }
}
