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
        User::create([
            'name' => 'Administrator',
            'account_name' => 'admin',
            'email' => 'admin@example.com',
            'phone_number' => '0123456789',
            'password' => Hash::make('123456'),
            'role_id' => 1, // Admin role
        ]);

        // User::create([
        //     'name' => 'Nguyễn Văn A',
        //     'account_name' => 'user1',
        //     'email' => 'user1@example.com',
        //     'phone_number' => '0987654321',
        //     'password' => Hash::make('password'),
        //     'role_id' => 2, // User role
        // ]);

        // User::create([
        //     'name' => 'Trần Thị B',
        //     'account_name' => 'staff1',
        //     'email' => 'staff1@example.com',
        //     'phone_number' => '0369852147',
        //     'password' => Hash::make('password'),
        //     'role_id' => 3, // Staff role
        // ]);
    }
} 