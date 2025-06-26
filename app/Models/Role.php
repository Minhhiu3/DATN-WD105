<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_role';
    protected $fillable = ['name', 'description', 'permissions'];
    public $timestamps = true;

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id_role');
    }

    /**
     * Check if role has specific permission
     */
    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return false;
        }
        
        return in_array($permission, $this->permissions);
    }

    /**
     * Check if role is admin
     */
    public function isAdmin()
    {
        return $this->name === 'Admin';
    }

    /**
     * Check if role is user
     */
    public function isUser()
    {
        return $this->name === 'User';
    }

    /**
     * Get role display name
     */
    public function getDisplayNameAttribute()
    {
        return ucfirst($this->name);
    }

    /**
     * Scope for admin role
     */
    public function scopeAdmin($query)
    {
        return $query->where('name', 'Admin');
    }

    /**
     * Scope for user role
     */
    public function scopeUser($query)
    {
        return $query->where('name', 'User');
    }

    /**
     * Get all available permissions
     */
    public static function getAvailablePermissions()
    {
        return [
            'user.view' => 'Xem danh sách người dùng',
            'user.create' => 'Tạo người dùng mới',
            'user.edit' => 'Chỉnh sửa người dùng',
            'user.delete' => 'Xóa người dùng',
            'product.view' => 'Xem danh sách sản phẩm',
            'product.create' => 'Tạo sản phẩm mới',
            'product.edit' => 'Chỉnh sửa sản phẩm',
            'product.delete' => 'Xóa sản phẩm',
            'category.view' => 'Xem danh sách danh mục',
            'category.create' => 'Tạo danh mục mới',
            'category.edit' => 'Chỉnh sửa danh mục',
            'category.delete' => 'Xóa danh mục',
            'order.view' => 'Xem danh sách đơn hàng',
            'order.edit' => 'Chỉnh sửa đơn hàng',
            'order.delete' => 'Xóa đơn hàng',
            'banner.view' => 'Xem danh sách banner',
            'banner.create' => 'Tạo banner mới',
            'banner.edit' => 'Chỉnh sửa banner',
            'banner.delete' => 'Xóa banner',
            'discount.view' => 'Xem danh sách mã giảm giá',
            'discount.create' => 'Tạo mã giảm giá mới',
            'discount.edit' => 'Chỉnh sửa mã giảm giá',
            'discount.delete' => 'Xóa mã giảm giá',
        ];
    }
} 