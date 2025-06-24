<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;


class AlbumProduct extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'album_product';


    protected $primaryKey = 'id_album_product';
    public $incrementing = true;
    protected $keyType = 'int';





    protected $fillable = [
        'product_id',
        'image'
    ];
    // public function products(): HasMany
    // {
    //     return $this->hasMany(Product::class, 'id_album_product', 'id_album_product');
    // }
    public function product(): BelongsTo
{
    return $this->belongsTo(Product::class, 'product_id', 'id_product');
}

}
