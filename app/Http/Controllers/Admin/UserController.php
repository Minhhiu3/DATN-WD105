<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('role');

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Lọc theo role
        if ($request->has('role') && $request->role) {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $users = $query->orderBy('id_user', 'asc')->paginate(10);
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|min:2',
            'account_name' => 'required|string|max:50|min:3|unique:users,account_name|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|email|max:100|unique:users,email',
            'phone_number' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'password' => 'required|string|min:6|max:255|confirmed',
            'role_id' => 'required|exists:roles,id_role',
            'status' => 'boolean',
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
            'role_id.required' => 'Vai trò là bắt buộc',
            'role_id.exists' => 'Vai trò không tồn tại',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => trim($request->name),
                'account_name' => trim($request->account_name),
                'email' => strtolower(trim($request->email)),
                'phone_number' => $request->phone_number ? trim($request->phone_number) : null,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'status' => $request->has('status'),
            ]);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Tài khoản đã được tạo thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Có lỗi xảy ra trong quá trình tạo tài khoản.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:100|min:2',
            'account_name' => ['required', 'string', 'max:50', 'min:3', 'regex:/^[a-zA-Z0-9_]+$/', Rule::unique('users', 'account_name')->ignore($id, 'id_user')],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($id, 'id_user')],
            'phone_number' => 'nullable|string|max:20|regex:/^[0-9+\-\s()]+$/',
            'password' => 'nullable|string|min:6|max:255|confirmed',
            'role_id' => 'required|exists:roles,id_role',
            'status' => 'boolean',
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
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.max' => 'Mật khẩu không được vượt quá 255 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'role_id.required' => 'Vai trò là bắt buộc',
            'role_id.exists' => 'Vai trò không tồn tại',
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => trim($request->name),
                'account_name' => trim($request->account_name),
                'email' => strtolower(trim($request->email)),
                'phone_number' => $request->phone_number ? trim($request->phone_number) : null,
                'role_id' => $request->role_id,
                'status' => $request->has('status'),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Tài khoản đã được cập nhật thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Có lỗi xảy ra trong quá trình cập nhật tài khoản.'])->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Không cho phép xóa tài khoản của chính mình
        if ($user->id_user === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể xóa tài khoản của chính mình!');
        }

        try {
            DB::beginTransaction();
            
            $user->delete();
            
            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Tài khoản đã được xóa thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.users.index')->with('error', 'Có lỗi xảy ra trong quá trình xóa tài khoản.');
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Không cho phép khóa tài khoản của chính mình
        if ($user->id_user === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'Không thể khóa tài khoản của chính mình!');
        }

        try {
            $user->update(['status' => !$user->status]);
            
            $status = $user->status ? 'kích hoạt' : 'khóa';
            return redirect()->route('admin.users.index')->with('success', "Tài khoản đã được {$status} thành công!");
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Có lỗi xảy ra trong quá trình thay đổi trạng thái tài khoản.');
        }
    }
}