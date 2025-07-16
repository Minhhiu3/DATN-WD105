<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
     protected $table = 'products';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id_product';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name_product',
        'price',
        'description',
        'category_id',
        'image',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    // public $timestamps = false; // Uncomment if you don't have timestamps

    /**
     * Get the category that owns the product.
     */






     
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }
    public function albumProducts(): HasMany
{
    // Giả sử khóa ngoại trong bảng `album_product` là 'product_id'
    // và khóa chính trong bảng `products` là 'id_product'
    return $this->hasMany(AlbumProduct::class, 'product_id', 'id_product');
}
public function variants()
{
    return $this->hasMany(Variant::class, 'product_id', 'id_product');
}

}
