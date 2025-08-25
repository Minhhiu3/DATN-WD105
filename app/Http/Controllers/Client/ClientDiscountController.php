<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DiscountCode;
use App\Models\UserVoucher;

class ClientDiscountController extends Controller
{

    public function index()
{
    $user = Auth::user();
    if($user){
        $discountCodes = DiscountCode::with(['userVouchers' => function($q) use ($user) {
            $q->where('user_id', $user->id_user);
        }])
        ->where('is_active', 1)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->where('program_type', 'choose_voucher')
        ->get();
    }else {
         $discountCodes = DiscountCode::where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->get();
    }
    // dd($discountCodes);

    return view('client.pages.discounts', compact('discountCodes'));
}
public function saveVoucherUser(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'discount_code_id' => 'required|exists:discount_codes,discount_id',
    ]);

    $exists = UserVoucher::where('user_id', $user->id_user)
              ->where('discount_id', $request->discount_code_id)
              ->exists();

    if ($exists) {
        return response()->json(['success' => false, 'message' => 'Bạn đã lưu mã này rồi']);
    }

    UserVoucher::create([
        'user_id' => $user->id_user,
        'discount_id' => $request->discount_code_id,
        'used' => false,
    ]);
    if (!Auth::check()) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn cần đăng nhập để lưu mã.'
        ], 401); // 401 để JS biết chuyển sang trang login
    }

    return response()->json(['success' => true, 'message' => 'Lưu mã thành công']);
}


    // public function save(Request $request)
    // {
    //     $user = Auth::user();
    //     $codeId = $request->discount_code_id;

    //     $alreadySaved = SavedDiscountCode::where('user_id', $user->id)
    //         ->where('discount_code_id', $codeId)
    //         ->exists();

    //     if (!$alreadySaved) {
    //         SavedDiscountCode::create([
    //             'user_id' => $user->id,
    //             'discount_code_id' => $codeId
    //         ]);
    //     }

    //     return back()->with('success', 'Đã lưu mã giảm giá');
    // }
}
