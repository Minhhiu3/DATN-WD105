<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlbumProduct extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'album_product';


    protected $primaryKey = 'id_album_product';




    protected $fillable = [
        'product_id',
        'image',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
