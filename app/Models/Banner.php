<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Banner extends Model{
    use HasFactory,SoftDeletes;

    protected $table = 'banner';
    
    protected $primaryKey = 'id_banner';

    protected $fillable  = [
        'name',
        'image', 
    ];
    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'id_banner', 'id_banner');
    }
}