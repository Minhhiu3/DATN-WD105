<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVoucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_vouchers';

    protected $primaryKey = 'id_user_voucher';

    protected $fillable = [
        'user_id',
        'discount_id',
        'used',
        'used_at',
    ];

    // protected $dates = [
    //     'used_at',
    //     'created_at',
    //     'updated_at',
    //     'deleted_at',
    // ];

    // Nếu bạn muốn tạo quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }


public function discount()
{
    return $this->belongsTo(DiscountCode::class, 'discount_id', 'discount_id');
}

}
