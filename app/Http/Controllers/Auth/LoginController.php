<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/');
            }
        }
        return view('client.pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // Kiểm tra xem user có tồn tại và có active không
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email không tồn tại trong hệ thống.',
            ])->withInput($request->only('email'));
        }

        if (!$user->isActive()) {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.',
            ])->withInput($request->only('email'));
        }

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Kiểm tra vai trò và chuyển hướng
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard')->with('success', 'Đăng nhập thành công! Chào mừng Admin.');
            } else {
                return redirect()->intended('/')->with('success', 'Đăng nhập thành công! Chào mừng ' . $user->display_name);
            }
        }

        return back()->withErrors([
            'password' => 'Mật khẩu không đúng.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $userName = $user ? $user->display_name : 'User';
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Đăng xuất thành công! Tạm biệt ' . $userName);
    }
}
