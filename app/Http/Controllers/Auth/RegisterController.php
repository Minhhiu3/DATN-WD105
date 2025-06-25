<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }
        return view('client.pages.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'account_name' => 'required|string|max:50|unique:users,account_name',
            'email' => 'required|email|max:100|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Họ tên là bắt buộc',
            'name.max' => 'Họ tên không được vượt quá 100 ký tự',
            'account_name.required' => 'Tên tài khoản là bắt buộc',
            'account_name.max' => 'Tên tài khoản không được vượt quá 50 ký tự',
            'account_name.unique' => 'Tên tài khoản đã tồn tại',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'phone_number.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        // Lấy role User (mặc định cho người đăng ký)
        $userRole = Role::where('name', 'User')->first();
        
        if (!$userRole) {
            return back()->withErrors(['error' => 'Không tìm thấy vai trò User. Vui lòng liên hệ admin.']);
        }

        $user = User::create([
            'name' => $request->name,
            'account_name' => $request->account_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role_id' => $userRole->id_role,
        ]);

        // Tự động đăng nhập sau khi đăng ký
        // Auth::login($user);

        return redirect('/login')->with('success', 'Đăng ký thành công! Chào mừng bạn đến với hệ thống.');
    }
} 