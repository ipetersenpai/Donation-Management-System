<?php

namespace App\Http\Controllers;

use App\Models\DonationCategory;
use Illuminate\Http\Request;

class DonationCategoryController extends Controller
{
    // This method retrieves all donation categories, paginated at 25 items per page, and returns them to the view 'pages.categories'.
    public function index()
    {
        $categories = DonationCategory::paginate(25);
        return view('pages.categories', compact('categories'));
    }

    // This method searches for donation categories based on search terms entered by the user. It splits the input into individual terms
    // and performs a search on 'category_name' and 'description' fields using the terms, returning paginated results.
    public function search(Request $request)
    {
        $search = $request->input('search');
        $searchTerms = explode(' ', $search);

        // Loops through each search term and applies a 'like' query to the 'category_name' or 'description' columns.
        $categories = DonationCategory::where(function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where('category_name', 'like', "%$term%")
                      ->orWhere('description', 'like', "%$term%");
            }
        })->paginate(10);

        return view('pages.categories', compact('categories'));
    }

    // This method validates input data and stores a new donation category in the database. It redirects back with a success message.
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'about'        => 'nullable|string',
            'link'         => 'nullable|string|max:255',
        ]);

        DonationCategory::create($validatedData);

        return redirect()->back()->with('success', 'Category created successfully');
    }

    // This method validates the input data and updates an existing donation category in the database.
    public function update(Request $request, DonationCategory $category)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'description'   => 'nullable|string',
            'about'         => 'nullable|string',
            'link'          => 'nullable|string|max:255',
        ]);

        $category->update($validatedData);

        return redirect()->back()->with('success', 'Category updated successfully');
    }

    // This method deletes a donation category. If an exception occurs, it catches the error and returns a failure message.
    public function destroy(DonationCategory $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Failed to delete category');
        }
    }
}
