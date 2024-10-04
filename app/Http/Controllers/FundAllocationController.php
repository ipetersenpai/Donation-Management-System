<?php

namespace App\Http\Controllers;

use App\Models\FundAllocation;
use App\Models\DonationCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Csv\Writer;
use Illuminate\Support\Facades\Response as FacadeResponse;

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

    // Exports the list of fund allocations as a CSV file.
    public function export()
    {
        // Fetch all fund allocations with their associated category.
        $allocations = FundAllocation::with('category')->get();

        // Creates a CSV writer instance from a string.
        $csv = Writer::createFromString('');
        // Inserts the header row into the CSV file.
        $csv->insertOne(['Category Name', 'Project Name', 'Allocated Amount', 'Created At', 'Updated At']);

        // Loops through each fund allocation and inserts the data as rows in the CSV.
        foreach ($allocations as $allocation) {
            $csv->insertOne([
                $allocation->category->category_name, // Category name from the related model
                $allocation->project_name,
                number_format($allocation->allocated_amount, 2), // Formats the allocated amount
                $allocation->created_at->format('m-d-Y'), // Formats the created_at date
                $allocation->updated_at->format('m-d-Y')  // Formats the updated_at date
            ]);
        }

        // Streams the CSV content as a downloadable file.
        return FacadeResponse::stream(
            function () use ($csv) {
                echo $csv;
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="fund_allocations.csv"',
            ]
        );
    }
}
