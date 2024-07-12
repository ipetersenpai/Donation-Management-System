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
        // Example user data
        $users = [
            [
                'first_name' => 'John',
                'middle_name' => 'Doe',
                'last_name' => 'Smith',
                'role' => 'Admin',
                'suffix' => 'Jr.',
                'birth_date' => '1990-01-01',
                'contact_no' => '1234567890',
                'home_address' => '123 Main St, Anytown',
                'gender' => 'Male',
                'email' => 'johndoe@example.com',
                'verified_status' => true,
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jane',
                'middle_name' => null,
                'last_name' => 'Doe',
                'role' => 'Donator',
                'suffix' => null,
                'birth_date' => '1995-05-15',
                'contact_no' => '0987654321',
                'home_address' => '456 Elm St, Othertown',
                'gender' => 'Female',
                'email' => 'jane@example.com',
                'verified_status' => false,
                'email_verified_at' => null,
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more users as needed
        ];

        // Insert the users into the database
        DB::table('users')->insert($users);
    }
}
