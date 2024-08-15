<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonationCategory;
use Illuminate\Support\Facades\Auth;

class DonationCategoryController extends Controller
{
    // Method to get donation categories with Bearer token
    public function index(Request $request)
    {
        // Ensure the request is authenticated
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch all donation categories
        $categories = DonationCategory::select('id', 'category_name', 'description')->get();

        return response()->json($categories);
    }
}
