<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'id_order';

    protected $fillable = [
    'user_id',
    'order_code',
    'shipping_fee',
    'total_amount',
    'payment_method',
    'payment_status',
    'status',
    'province',
    'district',
    'ward',
    'address',
    'email',
    'user_name',
    'phone',
    'grand_total',
    'created_at',
];



    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_order');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_order');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    // Địa chỉ đầy đủ dạng chuỗi
    public function fullAddress()
    {
        return "{$this->address}, {$this->ward}, {$this->district}, {$this->province}";
    }
}
