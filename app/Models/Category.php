<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'category';

    protected $primaryKey = 'id_category';

    protected $fillable = [
        'name_category',
    ];
 public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id_category');
    }

}
