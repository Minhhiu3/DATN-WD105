<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Color extends Model
{
    use HasFactory;
    use SoftDeletes; // Dung de xoa mem
    protected $table = 'colors';

    protected $primaryKey = 'id_color';

    protected $fillable = [
        'name_color',
        'image',
    ];
    // public function products(): HasMany
    // {
    //     return $this->hasMany(::class, 'id_color', 'id_color');
    // }
    public function variants()
{
    return $this->hasMany(Variant::class, 'color_id');
}
}
