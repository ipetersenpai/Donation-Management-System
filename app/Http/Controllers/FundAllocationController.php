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
    // Display the list of fund allocations
    public function index()
    {
        $allocations = FundAllocation::paginate(25);
        $categories = DonationCategory::all(); // Retrieve all categories

        return view('pages.fund_allocation', compact('allocations', 'categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:donation_categories,id',
            'project_name' => 'required|string|max:255',
            'allocated_amount' => 'required|numeric|min:0',
        ]);

        // Retrieve the category name based on the category_id
        $category = DonationCategory::find($validatedData['category_id']);

        FundAllocation::create([
            'category_id' => $validatedData['category_id'],
            'category_name' => $category->category_name, // Save the category name
            'project_name' => $validatedData['project_name'],
            'allocated_amount' => $validatedData['allocated_amount'],
        ]);

        return redirect()->back()->with('success', 'Fund allocated successfully');
    }

    // Update a fund allocation
    public function update(Request $request, FundAllocation $fundAllocation)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:donation_categories,id',
            'project_name' => 'required|string|max:255',
            'allocated_amount' => 'required|numeric|min:0',
        ]);

        $fundAllocation->update($validatedData);

        return redirect()->back()->with('success', 'Fund allocation updated successfully');
    }

    // Delete a fund allocation
    public function destroy(FundAllocation $fundAllocation)
    {
        try {
            $fundAllocation->delete();
            return redirect()->route('fund_allocations.index')->with('success', 'Fund allocation deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('fund_allocations.index')->with('error', 'Failed to delete fund allocation');
        }
    }

    public function totalAllocatedAmount()
{
    $totalAmount = FundAllocation::sum('allocated_amount');
    return response()->json(['total_allocated_amount' => $totalAmount]);
}

public function export()
{
    $allocations = FundAllocation::with('category')->get();

    $csv = Writer::createFromString('');
    $csv->insertOne(['Category Name', 'Project Name', 'Allocated Amount', 'Created At', 'Updated At']);

    foreach ($allocations as $allocation) {
        $csv->insertOne([
            $allocation->category->category_name,
            $allocation->project_name,
            number_format($allocation->allocated_amount, 2),
            $allocation->created_at->format('m-d-Y'),
            $allocation->updated_at->format('m-d-Y')
        ]);
    }

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
