<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    protected $primaryKey = 'id_cart_item';

    protected $fillable = [
        'cart_id',
        'variant_id',
        'quantity',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id_cart');
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'id_variant')->withTrashed();
    }

    public function getTotalPriceAttribute()
    {
        return $this->variant->price * $this->quantity;
    }
}
