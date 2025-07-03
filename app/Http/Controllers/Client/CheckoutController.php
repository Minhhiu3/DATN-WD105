<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    // Hiển thị form thanh toán
    public function showCheckoutForm(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variant,id_variant',
            'quantity'   => 'required|integer|min:1',
        ]);

        $variant = Variant::with(['product', 'size'])->findOrFail($request->variant_id);
        $quantity = $request->quantity;

        if ($variant->quantity < $quantity) {
            return redirect()->back()->withErrors('Số lượng sản phẩm không đủ trong kho.');
        }

        return view('client.pages.checkout', compact('variant', 'quantity'));
    }

    // Xử lý đặt hàng
    public function placeOrder(Request $request)
    {
        $request->validate([
            'variant_id'      => 'required|exists:variant,id_variant',
            'quantity'        => 'required|integer|min:1',
            'payment_method'  => 'required|in:cod,vnpay',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $variant = Variant::findOrFail($request->variant_id);

            if ($variant->quantity < $request->quantity) {
                return redirect()->back()->withErrors('Số lượng sản phẩm không đủ trong kho.');
            }

            // Tạo đơn hàng
            $order = Order::create([
                'user_id'        => $user->id_user,
                'status'         => 'pending',
                'payment_method' => $request->payment_method,
                'total_amount'   => $variant->price * $request->quantity,
                'created_at'     => now(),
            ]);

            // Thêm chi tiết đơn hàng
            OrderItem::create([
                'order_id'   => $order->id_order,
                'variant_id' => $variant->id_variant,
                'quantity'   => $request->quantity,
                'created_at' => now(),
            ]);

            // Trừ kho
            $variant->decrement('quantity', $request->quantity);

            DB::commit();
            return redirect()->route('home')->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors('Lỗi xử lý đơn hàng: ' . $e->getMessage());
        }
    }
}
