<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\DonationCategory;
use League\Csv\Writer; // Add this at the top if using the League CSV package
use Illuminate\Support\Facades\Response as FacadeResponse;
use Carbon\Carbon;

class DonationController extends Controller
{
    // Retrieves the donation history, including associated user and category data,
    // and returns it paginated with 25 records per page.
    public function getDonationHistory()
    {
        $donations = Donation::with(['user', 'category'])
            ->select('user_id', 'category_id', 'amount', 'reference_no', 'payment_option', 'created_at', 'non_member_full_name')
            ->paginate(25);

        // Fetch members for the modal dropdown
        $members = User::where('role', 'Member')->get();

        // Fetch distinct non-member names from donations
        $nonMembers = Donation::select('id', 'non_member_full_name')->whereNull('user_id')->distinct()->get();

        // Fetch donation categories for filtering or additional functionality
        $categories = DonationCategory::all();

        return view('pages.history', compact('donations', 'members', 'nonMembers', 'categories'));
    }

    public function storeDonation(Request $request)
    {
        $request->validate([
            'is_member' => 'required|boolean',
            'category_id' => 'required|exists:donation_categories,id', // Validation for the category
            'amount' => 'required|numeric',
            'reference_no' => 'required|string',
            'payment_option' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'name' => 'nullable|string',
        ]);

        if ($request->is_member && $request->user_id) {
            // If donor is a member, store the user_id
            $donation = Donation::create([
                'user_id' => $request->user_id,
                'category_id' => $request->category_id, // Store the selected category
                'amount' => $request->amount,
                'reference_no' => $request->reference_no,
                'payment_option' => $request->payment_option,
            ]);
        } else {
            // If donor is a non-member, store the non_member_full_name
            $donation = Donation::create([
                'non_member_full_name' => $request->name,
                'category_id' => $request->category_id, // Store the selected category
                'amount' => $request->amount,
                'reference_no' => $request->reference_no,
                'payment_option' => $request->payment_option,
            ]);
        }

        return redirect()->route('donation.history')->with('success', 'Donation added successfully!');
    }

    // Searches for donations based on the search input, allowing queries to match against user names,
    // donation categories, reference numbers, payment options, and amounts.
    public function searchDonations(Request $request)
    {
        $query = $request->input('search');

        // Fetch donations based on search query
        $donations = Donation::with(['user', 'category'])
            ->where(function ($q) use ($query) {
                // Search for donations where the user's first, middle, or last name matches the query.
                $q->whereHas('user', function ($q) use ($query) {
                    $q->where('first_name', 'like', '%' . $query . '%')
                        ->orWhere('middle_name', 'like', '%' . $query . '%')
                        ->orWhere('last_name', 'like', '%' . $query . '%');
                })
                    // Or search donations where the non-member's full name matches the query.
                    ->orWhere('non_member_full_name', 'like', '%' . $query . '%');
            })
            // Search donations where the category name matches the query.
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('category_name', 'like', '%' . $query . '%');
            })
            // Search donations based on reference number, payment option, or donation amount.
            ->orWhere('reference_no', 'like', '%' . $query . '%')
            ->orWhere('payment_option', 'like', '%' . $query . '%')
            ->orWhere('amount', 'like', '%' . $query . '%')
            ->select('user_id', 'non_member_full_name', 'category_id', 'amount', 'reference_no', 'payment_option', 'created_at')
            ->paginate(25);

        // Fetch members for modal
        $members = User::where('role', 'Member')->get();

        // Fetch categories for modal
        $categories = DonationCategory::all();

        // Pass donations, members, and categories to the view
        return view('pages.history', compact('donations', 'members', 'categories'));
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

    public function exportDonations(Request $request)
    {
        // Validate inputs
        $validatedData = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'donator_id' => 'nullable|string', // Donator ID can be member or non-member
        ]);

        // Build the query
        $donationsQuery = Donation::with(['user', 'category']);

        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $donationsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Filter by donator
        if ($request->filled('donator_id')) {
            if (str_starts_with($request->donator_id, 'non_member_')) {
                // Extract non-member ID
                $nonMemberId = str_replace('non_member_', '', $request->donator_id);
                $donationsQuery->where('id', $nonMemberId)->whereNull('user_id');
            } else {
                // Member ID
                $donationsQuery->where('user_id', $request->donator_id);
            }
        }

        // Fetch donations
        $donations = $donationsQuery->get();

        // Create a CSV writer
        $csv = Writer::createFromString('');

        // Insert the header row
        $csv->insertOne(['Donator Name', 'Category Name', 'Amount', 'Reference No', 'Payment Option', 'Created At']);

        // Insert donation data
        foreach ($donations as $donation) {
            $donatorName = $donation->user ? $donation->user->first_name . ' ' . $donation->user->middle_name . ' ' . $donation->user->last_name : $donation->non_member_full_name;

            $csv->insertOne([$donatorName, $donation->category->category_name, number_format($donation->amount, 2), $donation->reference_no, $donation->payment_option, $donation->created_at->format('m-d-Y')]);
        }

        // Stream the CSV file
        return FacadeResponse::stream(
            function () use ($csv) {
                echo $csv;
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="donation_history.csv"',
            ],
        );
    }
}
