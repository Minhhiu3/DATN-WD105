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
        'brand_id', // Giữ lại để lưu khóa ngoại
        'image',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id_category');
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id_brand');
    }

    public function albumProducts(): HasMany
    {
        return $this->hasMany(AlbumProduct::class, 'product_id', 'id_product');
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'product_id', 'id_product');
    }

    public function albums()
    {
        return $this->hasMany(AlbumProduct::class, 'product_id', 'id_product');
    }

    public function advice_product()
    {
        return $this->hasOne(AdviceProduct::class, 'product_id', 'id_product');
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id', 'id_product');
    }

    public function adviceProduct()
    {
        return $this->hasOne(AdviceProduct::class, 'product_id');
    }
}