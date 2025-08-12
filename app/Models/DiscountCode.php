<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountCode extends Model
{
    use HasFactory,SoftDeletes;

   protected $table = 'discount_codes';
    protected $primaryKey = 'discount_id';

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_value',
        'user_specific',
        'start_date',
        'end_date',
        'is_active',
    ];
        public function userVouchers()
{
    return $this->hasMany(UserVoucher::class, 'discount_id', 'discount_id');
}
}
