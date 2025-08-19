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
        'color_id',
        'price',
        'quantity',
    ];
protected $dates = ['deleted_at'];

    public function product()
{
    return $this->belongsTo(Product::class, 'product_id', 'id_product')->withTrashed();
}

public function size()
{
    return $this->belongsTo(Size::class, 'size_id', 'id_size');
}
// public function color()
// {
//     return $this->belongsTo(Color::class, 'color_id', 'id_color');
// }
public function adviceProduct()
{
    return $this->hasOne(AdviceProduct::class, 'product_id', 'product_id')
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->where('status', 'on');
}

public function color()
{
    return $this->belongsTo(Color::class, 'color_id', 'id_color')->withTrashed();
}


}
