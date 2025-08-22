<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'];
        
        $users = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'age' => 28, 'city' => 'New York'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'age' => 32, 'city' => 'Los Angeles'],
            ['name' => 'Mike Johnson', 'email' => 'mike@example.com', 'age' => 25, 'city' => 'Chicago'],
            ['name' => 'Sarah Wilson', 'email' => 'sarah@example.com', 'age' => 29, 'city' => 'Houston'],
            ['name' => 'David Brown', 'email' => 'david@example.com', 'age' => 35, 'city' => 'Phoenix'],
            ['name' => 'Lisa Davis', 'email' => 'lisa@example.com', 'age' => 27, 'city' => 'Philadelphia'],
            ['name' => 'Tom Miller', 'email' => 'tom@example.com', 'age' => 31, 'city' => 'San Antonio'],
            ['name' => 'Amy Garcia', 'email' => 'amy@example.com', 'age' => 26, 'city' => 'San Diego'],
            ['name' => 'Chris Rodriguez', 'email' => 'chris@example.com', 'age' => 33, 'city' => 'Dallas'],
            ['name' => 'Emma Martinez', 'email' => 'emma@example.com', 'age' => 24, 'city' => 'San Jose'],
            ['name' => 'Alex Thompson', 'email' => 'alex@example.com', 'age' => 30, 'city' => 'New York'],
            ['name' => 'Rachel Lee', 'email' => 'rachel@example.com', 'age' => 28, 'city' => 'Los Angeles'],
            ['name' => 'Kevin White', 'email' => 'kevin@example.com', 'age' => 34, 'city' => 'Chicago'],
            ['name' => 'Maria Clark', 'email' => 'maria@example.com', 'age' => 29, 'city' => 'Houston'],
            ['name' => 'James Lewis', 'email' => 'james@example.com', 'age' => 27, 'city' => 'Phoenix'],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'age' => $userData['age'],
                'city' => $userData['city'],
                'email_verified_at' => now(),
            ]);
        }
    }
}