<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;

class AuthHelper
{
    /**
     * Kiểm tra user hiện tại có phải là admin không
     */
    public static function isAdmin()
    {
        return Auth::check() && Auth::user()->isAdmin();
    }

    /**
     * Kiểm tra user hiện tại có quyền cụ thể không
     */
    public static function hasPermission($permission)
    {
        if (!Auth::check()) {
            return false;
        }

        $user = Auth::user();
        return $user->role && $user->role->hasPermission($permission);
    }

    /**
     * Kiểm tra user hiện tại có role cụ thể không
     */
    public static function hasRole($roleName)
    {
        return Auth::check() && Auth::user()->hasRole($roleName);
    }

    /**
     * Lấy thông tin user hiện tại
     */
    public static function currentUser()
    {
        return Auth::user();
    }

    /**
     * Kiểm tra user hiện tại có active không
     */
    public static function isActive()
    {
        return Auth::check() && Auth::user()->isActive();
    }

    /**
     * Lấy tên hiển thị của user hiện tại
     */
    public static function displayName()
    {
        return Auth::check() ? Auth::user()->display_name : 'Guest';
    }

    /**
     * Lấy role của user hiện tại
     */
    public static function currentRole()
    {
        return Auth::check() ? Auth::user()->role : null;
    }

    /**
     * Kiểm tra user có thể truy cập admin panel không
     */
    public static function canAccessAdmin()
    {
        return self::isAdmin() && self::isActive();
    }

    /**
     * Lấy danh sách tất cả permissions
     */
    public static function getAllPermissions()
    {
        return Role::getAvailablePermissions();
    }

    /**
     * Lấy danh sách permissions của user hiện tại
     */
    public static function getUserPermissions()
    {
        if (!Auth::check()) {
            return [];
        }

        $user = Auth::user();
        return $user->role ? $user->role->permissions : [];
    }

    /**
     * Kiểm tra user có thể thực hiện hành động CRUD không
     */
    public static function can($action, $resource)
    {
        $permission = $resource . '.' . $action;
        return self::hasPermission($permission);
    }

    /**
     * Lấy thông tin đăng nhập của user
     */
    public static function getLoginInfo()
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();
        return [
            'id' => $user->id_user,
            'name' => $user->name,
            'email' => $user->email,
            'account_name' => $user->account_name,
            'role' => $user->role ? $user->role->name : null,
            'status' => $user->status,
            'last_login' => $user->updated_at,
        ];
    }
} 