<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class AdviceProduct extends Model
{
    use HasFactory, SoftDeletes;

    // Khai báo bảng tương ứng
    protected $table = 'advice_product';

    // Khóa chính của bảng
    protected $primaryKey = 'id_advice';

    // Cho phép Laravel quản lý timestamps
    public $timestamps = true;

    // Các cột có thể gán dữ liệu
    protected $fillable = [
        'product_id',
        'value',
        'start_date',
        'end_date',
        'status',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id_product');
    }
}
