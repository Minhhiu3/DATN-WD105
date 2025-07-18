<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Size extends Model
{
    use HasFactory;
    use SoftDeletes; // Dung de xoa mem
    protected $table = 'size';

    protected $primaryKey = 'id_size';

    protected $fillable = [
        'name',
    ];
    // public function products(): HasMany
    // {
    //     return $this->hasMany(::class, 'id_size', 'id_size');
    // }
    public function variants()
{
    return $this->hasMany(Variant::class, 'size_id');
}
}
