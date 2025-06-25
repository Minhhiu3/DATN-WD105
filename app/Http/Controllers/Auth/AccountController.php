<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Hiển thị thông tin tài khoản
     */
    public function profile()
    {
        $user = Auth::user()->load('role');
        return view('auth.profile', compact('user'));
    }

    /**
     * Hiển thị form chỉnh sửa thông tin
     */
    public function edit()
    {
        $user = Auth::user();
        $roles = Role::all();
        return view('auth.edit', compact('user', 'roles'));
    }

    /**
     * Cập nhật thông tin tài khoản
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:100',
            'account_name' => ['required', 'string', 'max:50', 'unique:users,account_name,' . $user->id_user . ',id_user'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id_user . ',id_user'],
            'phone_number' => 'nullable|string|max:20',
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
        ]);

        $user->update([
            'name' => $request->name,
            'account_name' => $request->account_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
        ]);

        return redirect()->route('account.profile')->with('success', 'Thông tin tài khoản đã được cập nhật thành công!');
    }

    /**
     * Hiển thị form đổi mật khẩu
     */
    public function changePassword()
    {
        return view('auth.change-password');
    }

    /**
     * Cập nhật mật khẩu
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Mật khẩu hiện tại là bắt buộc',
            'password.required' => 'Mật khẩu mới là bắt buộc',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $user = Auth::user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('account.profile')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }

    /**
     * Hiển thị lịch sử đơn hàng
     */
    public function orders()
    {
        $user = Auth::user();
        // TODO: Thêm logic lấy đơn hàng của user
        $orders = collect(); // Tạm thời để trống
        return view('auth.orders', compact('orders'));
    }

    /**
     * Hiển thị thông tin cá nhân
     */
    public function settings()
    {
        $user = Auth::user()->load('role');
        return view('auth.settings', compact('user'));
    }
}
