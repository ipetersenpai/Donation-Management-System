<?php

namespace App\Http\Controllers;

use App\Models\FundAllocation;
use App\Models\DonationCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Csv\Writer;
use App\Models\Donation;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Carbon\Carbon; // Import Carbon
use Barryvdh\DomPDF\Facade\Pdf; // Import DomPDF

class FundAllocationController extends Controller
{
    // Displays a paginated list of fund allocations, along with the donation categories.
    public function index()
    {
        $allocations = FundAllocation::paginate(25); // Fetch fund allocations paginated
        $categories = DonationCategory::all(); // Retrieve all donation categories

        // Passes the allocations and categories to the view
        return view('pages.fund_allocation', compact('allocations', 'categories'));
    }

    // Stores a new fund allocation record after validating the input data.
    public function store(Request $request)
    {
        // Validates the form input data for required fields and types.
        $validatedData = $request->validate([
            'category_id' => 'required|exists:donation_categories,id',
            'project_name' => 'required|string|max:255',
            'allocated_amount' => 'required|numeric|min:0',
        ]);

        // Retrieves the category based on the category_id.
        $category = DonationCategory::find($validatedData['category_id']);

        // Creates a new fund allocation record with the validated data.
        FundAllocation::create([
            'category_id' => $validatedData['category_id'],
            'category_name' => $category->category_name, // Store category name from the retrieved category
            'project_name' => $validatedData['project_name'],
            'allocated_amount' => $validatedData['allocated_amount'],
        ]);

        // Redirects back to the form with a success message.
        return redirect()->back()->with('success', 'Fund allocated successfully');
    }

    // Updates an existing fund allocation after validating the request data.
    public function update(Request $request, FundAllocation $fundAllocation)
    {
        // Validates the form input data before updating the record.
        $validatedData = $request->validate([
            'category_id' => 'required|exists:donation_categories,id',
            'project_name' => 'required|string|max:255',
            'allocated_amount' => 'required|numeric|min:0',
        ]);

        // Updates the fund allocation record with the validated data.
        $fundAllocation->update($validatedData);

        // Redirects back to the form with a success message.
        return redirect()->back()->with('success', 'Fund allocation updated successfully');
    }

    // Deletes a fund allocation record.
    public function destroy(FundAllocation $fundAllocation)
    {
        try {
            // Attempts to delete the fund allocation record.
            $fundAllocation->delete();
            return redirect()->route('fund_allocations.index')->with('success', 'Fund allocation deleted successfully');
        } catch (\Exception $e) {
            // If deletion fails, an error message is returned.
            return redirect()->route('fund_allocations.index')->with('error', 'Failed to delete fund allocation');
        }
    }

    // Returns the total allocated amount by summing the 'allocated_amount' column of the fund allocations.
    public function totalAllocatedAmount()
    {
        $totalAmount = FundAllocation::sum('allocated_amount');
        return response()->json(['total_allocated_amount' => $totalAmount]);
    }

    public function remainingBalance()
    {
        // Fetch the total amount donated and total fund allocation
        $totalAmountDonated = Donation::sum('amount');
        $totalFundAllocated = FundAllocation::sum('allocated_amount');

        // Calculate remaining balance
        $remainingBalance =  $totalAmountDonated - $totalFundAllocated;

        // Return data for dashboard
        return response()->json([
            'remaining_balance' => $remainingBalance,
        ]);
    }

    // Exports the list of fund allocations as a CSV file.
    // Exports the list of fund allocations as a PDF file.
    public function exportToPDF(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:donation_categories,id',
        ]);

        // Fetch fund allocations with optional filters
        $allocationsQuery = FundAllocation::with('category');

        if ($request->filled('category_id')) {
            $allocationsQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $allocationsQuery->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        $allocations = $allocationsQuery->get();

        // Get the start and end dates for the view
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        // Load data into PDF view
        $pdf = Pdf::loadView('exports.fund_allocations_pdf', compact('allocations', 'start_date', 'end_date'));

        // Return PDF for download
        return $pdf->download('fund_allocations.pdf');
    }
}
