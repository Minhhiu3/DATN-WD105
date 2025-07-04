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
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_order');
    }
}
