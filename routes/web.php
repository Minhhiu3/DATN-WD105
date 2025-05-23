<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Giao diện người dùng
Route::get('/', [App\Http\Controllers\User\HomeController::class, 'index']);

// // Giao diện admin (cần đăng nhập và là admin)
// Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

//     // Thêm route quản lý user, sản phẩm,...
// });

Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);