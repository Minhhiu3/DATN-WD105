<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    use HasFactory, SoftDeletes;
     protected $table = 'product_reviews';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id_review';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'comment',
        'image_url',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }

     public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order');
    }

}
