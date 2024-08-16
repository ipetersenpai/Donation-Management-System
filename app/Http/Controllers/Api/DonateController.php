<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DonateController extends Controller
{
    public function store(Request $request)
    {
        // Handle the file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment_file')) {
            $file = $request->file('attachment_file');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            // Create image manager with desired driver
            $manager = new ImageManager(new Driver());

            // Read image from file system
            $image = $manager->read($file->getPathname());

            // Resize and compress the image
            $image->resize(800, 800, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            // Define the path to save the image
            $attachmentPath = 'uploads/proof_of_donation' . $filename;
            $image->save(public_path($attachmentPath), 75); // Save with 75% quality to compress
        }

        // Create donation record
        $donation = new Donation();
        $donation->user_id = Auth::id(); // Get the authenticated user ID
        $donation->category_id = $request->input('category_id');
        $donation->payment_option = $request->input('payment_option');
        $donation->amount = $request->input('amount');
        $donation->attachment_file = $attachmentPath;
        $donation->reference_no = $request->input('reference_no');
        $donation->save();

        return response()->json([
            'message' => 'Thank you so much for your generous donation! Your kindness and support mean the world to us and will make a significant impact in the lives of those we help.',
            'donation' => $donation
        ], 201);
    }
}
