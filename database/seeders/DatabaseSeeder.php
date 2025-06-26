<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo roles mặc định
        $this->createDefaultRoles();
        
        // Tạo admin user mặc định
        $this->createDefaultAdmin();
        
        // Chạy các seeder khác
        $this->call([
            BannerSeeder::class,
            ProductSeeder::class,
        ]);
    }

    /**
     * Tạo roles mặc định
     */
    private function createDefaultRoles()
    {
        // Tạo role Admin
        Role::firstOrCreate(
            ['name' => 'Admin'],
            [
                'description' => 'Quản trị viên hệ thống với tất cả quyền',
                'permissions' => [
                    'user.view', 'user.create', 'user.edit', 'user.delete',
                    'product.view', 'product.create', 'product.edit', 'product.delete',
                    'category.view', 'category.create', 'category.edit', 'category.delete',
                    'order.view', 'order.edit', 'order.delete',
                    'banner.view', 'banner.create', 'banner.edit', 'banner.delete',
                    'discount.view', 'discount.create', 'discount.edit', 'discount.delete',
                ],
            ]
        );

        // Tạo role User
        Role::firstOrCreate(
            ['name' => 'User'],
            [
                'description' => 'Người dùng thông thường',
                'permissions' => [
                    'product.view',
                    'order.view',
                ],
            ]
        );
    }

    /**
     * Tạo admin user mặc định
     */
    private function createDefaultAdmin()
    {
        $adminRole = Role::where('name', 'Admin')->first();
        
        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Administrator',
                    'account_name' => 'admin',
                    'email' => 'admin@example.com',
                    'phone_number' => '0123456789',
                    'password' => Hash::make('123456'),
                    'role_id' => $adminRole->id_role,
                    'status' => true,
                ]
            );
        }
    }
}