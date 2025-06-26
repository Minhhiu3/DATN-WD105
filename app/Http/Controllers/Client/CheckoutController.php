<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    //

  public function showCheckoutForm(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variant,id_variant',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = Variant::with(['product', 'size'])->findOrFail($request->variant_id);
        $quantity = $request->quantity;

        if ($variant->quantity < $quantity) {
            return redirect()->back()->withErrors('Số lượng sản phẩm không đủ.');
        }

        return view('client.pages.checkout', compact('variant', 'quantity'));
    }

    // Đặt hàng
    public function placeOrder(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variant,id_variant',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $user = Auth::user();
            $variant = Variant::findOrFail($request->variant_id);

            if ($variant->quantity < $request->quantity) {
                return redirect()->back()->withErrors('Số lượng sản phẩm không đủ.');
            }

            // Tạo đơn hàng
            $order = new Order();
            $order->user_id = $user->id_user;
            $order->status = 'chờ xác nhận';
            $order->created_at = now();
            $order->save();

            // Tạo chi tiết đơn hàng
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id_order;
            $orderItem->variant_id = $variant->id_variant;
            $orderItem->quantity = $request->quantity;
            $orderItem->payment_method = $request->payment_method;
            $orderItem->total_amount = $variant->price * $request->quantity;
            $orderItem->created_at = now();
            $orderItem->save();

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
