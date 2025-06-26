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
    public function __construct()
    {
        $this->middleware('guest');
    }

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
            'name' => 'required|string|max:100|min:2',
            'account_name' => 'required|string|max:50|min:3|unique:users,account_name|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|email|max:100|unique:users,email',
            'phone_number' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'password' => 'required|string|min:6|max:255|confirmed',
        ], [
            'name.required' => 'Họ tên là bắt buộc',
            'name.min' => 'Họ tên phải có ít nhất 2 ký tự',
            'name.max' => 'Họ tên không được vượt quá 100 ký tự',
            'account_name.required' => 'Tên tài khoản là bắt buộc',
            'account_name.min' => 'Tên tài khoản phải có ít nhất 3 ký tự',
            'account_name.max' => 'Tên tài khoản không được vượt quá 50 ký tự',
            'account_name.unique' => 'Tên tài khoản đã tồn tại',
            'account_name.regex' => 'Tên tài khoản chỉ được chứa chữ cái, số và dấu gạch dưới',
            'email.required' => 'Email là bắt buộc',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'phone_number.regex' => 'Số điện thoại không đúng định dạng',
            'phone_number.max' => 'Số điện thoại không được vượt quá 20 ký tự',
            'password.required' => 'Mật khẩu là bắt buộc',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu không được vượt quá 255 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        try {
            // Lấy role User (mặc định cho người đăng ký)
            $userRole = Role::where('name', 'User')->first();
            
            if (!$userRole) {
                return back()->withErrors(['error' => 'Không tìm thấy vai trò User. Vui lòng liên hệ admin.'])->withInput();
            }

            $user = User::create([
                'name' => trim($request->name),
                'account_name' => trim($request->account_name),
                'email' => strtolower(trim($request->email)),
                'phone_number' => $request->phone_number ? trim($request->phone_number) : null,
                'password' => Hash::make($request->password),
                'role_id' => $userRole->id_role,
                'status' => true,
            ]);

            return redirect('/login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập để sử dụng tài khoản.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Có lỗi xảy ra trong quá trình đăng ký. Vui lòng thử lại.'])->withInput();
        }
    }
} 