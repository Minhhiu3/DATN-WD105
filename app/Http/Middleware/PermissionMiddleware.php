<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để truy cập trang này.');
        }

        $user = Auth::user();
        
        // Kiểm tra trạng thái tài khoản
        if (!$user->isActive()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tài khoản đã bị khóa.'], 403);
            }
            return redirect('/login')->with('error', 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ admin.');
        }
        
        // Kiểm tra quyền
        if (!$user->role || !$user->role->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied.'], 403);
            }
            return redirect('/')->with('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        return $next($request);
    }
} 