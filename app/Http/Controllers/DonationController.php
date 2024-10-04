<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    // Retrieves the donation history, including associated user and category data,
    // and returns it paginated with 25 records per page.
    public function getDonationHistory()
    {
        $donations = Donation::with(['user', 'category'])
            ->select('user_id', 'category_id', 'amount', 'reference_no', 'payment_option', 'created_at')
            ->paginate(25);

        // Passes the paginated donation data to the 'pages.history' view.
        return view('pages.history', compact('donations'));
    }

    // Searches for donations based on the search input, allowing queries to match against user names,
    // donation categories, reference numbers, payment options, and amounts.
    public function searchDonations(Request $request)
    {
        $query = $request->input('search');

        $donations = Donation::with(['user', 'category'])
            // Search donations where the user's first, middle, or last name matches the query.
            ->whereHas('user', function ($q) use ($query) {
                $q->where('first_name', 'like', '%' . $query . '%')
                    ->orWhere('middle_name', 'like', '%' . $query . '%')
                    ->orWhere('last_name', 'like', '%' . $query . '%');
            })
            // Search donations where the category name matches the query.
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('category_name', 'like', '%' . $query . '%');
            })
            // Search donations based on reference number, payment option, or donation amount.
            ->orWhere('reference_no', 'like', '%' . $query . '%')
            ->orWhere('payment_option', 'like', '%' . $query . '%')
            ->orWhere('amount', 'like', '%' . $query . '%')
            ->select('user_id', 'category_id', 'amount', 'reference_no', 'payment_option', 'created_at')
            ->paginate(25);

        // Passes the filtered donation data to the 'pages.history' view.
        return view('pages.history', compact('donations'));
    }

    // Counts the distinct number of users who have made a donation.
    public function countUsersWhoDonated()
    {
        // The distinct user count is calculated by counting unique 'user_id' values.
        $userCount = Donation::distinct('user_id')->count('user_id');

        // Returns the count as a JSON response.
        return response()->json(['total_users' => $userCount]);
    }

    // Calculates the total amount of money donated across all donations.
    public function totalAmountDonated()
    {
        // The total amount donated is calculated by summing the 'amount' column.
        $totalAmount = Donation::sum('amount');

        // Returns the total amount as a JSON response.
        return response()->json(['total_amount' => $totalAmount]);
    }
}
