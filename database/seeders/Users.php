<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Generate 100 users
        for ($i = 6; $i <= 123; $i++) {
            $users[] = [
                'first_name' => 'User' . $i,
                'middle_name' => null,
                'last_name' => 'Doe',
                'role' => 'Member',
                'suffix' => null,
                'birth_date' => now()->subYears(rand(18, 60))->format('Y-m-d'), // Random birth date between 18 to 60 years ago
                'contact_no' => '1234567890', // Dummy contact number
                'home_address' => 'Address ' . $i,
                'gender' => rand(0, 1) ? 'Male' : 'Female', // Random gender
                'email' => 'user' . $i . '@example.com',
                'verified_status' => rand(0, 1), // Random verified status
                'email_verified_at' => rand(0, 1) ? now() : null,
                'password' => Hash::make('password'), // Default password for all users
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        // Insert the users into the database
        DB::table('users')->insert($users);
    }
}
