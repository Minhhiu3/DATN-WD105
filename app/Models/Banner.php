<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Banner extends Model
{
    use SoftDeletes;
    protected $table = 'banners';
    protected $primaryKey = 'id';

    protected $fillable = ['name', 'image', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
