<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
     protected $table = 'order_items';
    protected $primaryKey = 'id_order_item';


    protected $fillable = [
        'order_id',
        'variant_id',
        'quantity',
        'payment_method',
        'total_amount',

    ];


    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order');
    }


    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'id_variant');
    }
        public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id_size');
    }
    public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id_product');
}
}
