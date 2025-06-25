<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AuthHelper
{
    /**
     * Kiểm tra người dùng có phải là Admin không
     */
    public static function isAdmin()
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return $user->role && $user->role->name === 'Admin';
    }

    /**
     * Kiểm tra người dùng có phải là Staff không
     */
    public static function isStaff()
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return $user->role && $user->role->name === 'Staff';
    }

    /**
     * Kiểm tra người dùng có phải là User thường không
     */
    public static function isUser()
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return $user->role && $user->role->name === 'User';
    }

    /**
     * Kiểm tra người dùng có quyền truy cập không
     */
    public static function hasRole($roleName)
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return $user->role && $user->role->name === $roleName;
    }

    /**
     * Lấy tên vai trò của người dùng hiện tại
     */
    public static function getUserRole()
    {
        if (!Auth::check()) {
            return null;
        }
        
        $user = Auth::user();
        return $user->role ? $user->role->name : null;
    }
} 