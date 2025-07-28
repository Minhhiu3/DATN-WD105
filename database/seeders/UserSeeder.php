<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['account_name' => 'admin'], // điều kiện kiểm tra
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'phone_number' => '0123456789',
                'password' => Hash::make('123456'),
                'role_id' => 1,
            ]
        );

        User::updateOrCreate(
            ['account_name' => 'user1'],
            [
                'name' => 'Nguyễn Văn A',
                'email' => 'user1@example.com',
                'phone_number' => '0987654321',
                'password' => Hash::make('123456'),
                'role_id' => 2,
            ]
        );

        // Thêm nếu bạn muốn seed thêm staff
        // User::updateOrCreate(
        //     ['account_name' => 'staff1'],
        //     [
        //         'name' => 'Trần Thị B',
        //         'email' => 'staff1@example.com',
        //         'phone_number' => '0369852147',
        //         'password' => Hash::make('password'),
        //         'role_id' => 3,
        //     ]
        // );
    }
}
