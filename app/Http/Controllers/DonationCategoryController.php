<?php

namespace App\Http\Controllers;

use App\Models\DonationCategory;
use Illuminate\Http\Request;

class DonationCategoryController extends Controller
{
    public function index()
    {
        $categories = DonationCategory::paginate(25);
        return view('pages.categories', compact('categories'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $searchTerms = explode(' ', $search);

        $categories = DonationCategory::where(function ($query) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $query->where('category_name', 'like', "%$term%")->orWhere('description', 'like', "%$term%");
            }
        })->paginate(10);

        return view('pages.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DonationCategory::create($validatedData);

        return redirect()->back()->with('success', 'Category created successfully');
    }

    public function update(Request $request, DonationCategory $category)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validatedData);

        return redirect()->back()->with('success', 'Category updated successfully');
    }

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
