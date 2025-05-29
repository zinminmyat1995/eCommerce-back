<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone_number' => '555-1234',
                'address' => '123 Elm Street',
                'city' => 'New York',
                'state' => 'NY',
                'postal_code' => '10001',
                'country' => 'USA',
                'date_of_birth' => '1990-05-15',
                'gender' => 'Male',
                'password' => Hash::make('password123'),
                'loyalty_points' => 120,
                'is_active' => true,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@example.com',
                'phone_number' => '555-5678',
                'address' => '456 Oak Avenue',
                'city' => 'Los Angeles',
                'state' => 'CA',
                'postal_code' => '90001',
                'country' => 'USA',
                'date_of_birth' => '1985-11-22',
                'gender' => 'Female',
                'password' => Hash::make('password123'),
                'loyalty_points' => 200,
                'is_active' => true,
            ],
            [
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike.johnson@example.com',
                'phone_number' => '555-8765',
                'address' => '789 Pine Road',
                'city' => 'Chicago',
                'state' => 'IL',
                'postal_code' => '60007',
                'country' => 'USA',
                'date_of_birth' => '1992-02-10',
                'gender' => 'Male',
                'password' => Hash::make('password123'),
                'loyalty_points' => 150,
                'is_active' => true,
            ],
            [
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'email' => 'emily.davis@example.com',
                'phone_number' => '555-3456',
                'address' => '101 Maple Drive',
                'city' => 'Houston',
                'state' => 'TX',
                'postal_code' => '77001',
                'country' => 'USA',
                'date_of_birth' => '1988-08-30',
                'gender' => 'Female',
                'password' => Hash::make('password123'),
                'loyalty_points' => 50,
                'is_active' => true,
            ],
        ]);
    }
}