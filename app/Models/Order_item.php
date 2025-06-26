<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order_item extends Model
{
    use HasFactory;
    protected $table = 'order_items'; 


    protected $primaryKey = 'id_order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'variant_id',
        'quantity',
        'payment_method',
    ];
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order');
    }
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class, 'variant_id', 'id_variant');
    }
    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id', 'id_size');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
