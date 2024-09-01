<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    public function getDonationHistory()
    {
        $donations = Donation::with(['user', 'category'])
            ->select('user_id', 'category_id', 'amount', 'reference_no', 'payment_option', 'created_at')
            ->paginate(25);

        return view('pages.history', compact('donations'));
    }

    public function searchDonations(Request $request)
    {
        $query = $request->input('search');

        $donations = Donation::with(['user', 'category'])
            ->whereHas('user', function ($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                    ->orWhere('middle_name', 'like', '%' . $query . '%')
                    ->orWhere('last_name', 'like', '%' . $query . '%');
            })
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('category_name', 'like', '%' . $query . '%');
            })
            ->orWhere('reference_no', 'like', '%' . $query . '%')
            ->orWhere('payment_option', 'like', '%' . $query . '%')
            ->orWhere('amount', 'like', '%' . $query . '%')
            ->select('user_id', 'category_id', 'amount', 'reference_no', 'payment_option', 'created_at')
            ->paginate(25);

        return view('pages.history', compact('donations'));
    }

    public function countUsersWhoDonated()
    {
        $userCount = Donation::distinct('user_id')->count('user_id');
        return response()->json(['total_users' => $userCount]);
    }

    public function totalAmountDonated()
    {
        $totalAmount = Donation::sum('amount');
        return response()->json(['total_amount' => $totalAmount]);
    }
}
