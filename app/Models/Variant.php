<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variant extends Model
{
    use HasFactory;
    use SoftDeletes; // Dung de xoa mem
    protected $table = 'variant';
    protected $primaryKey = 'id_variant';

    protected $fillable = [
        'size_id',
        'product_id',
        'price',
        'quantity',
    ];
    public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id_product')->withTrashed();
}

public function size()
{
    return $this->belongsTo(Size::class, 'size_id', 'id_size');
}

}
