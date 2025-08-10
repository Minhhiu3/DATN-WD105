<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    //
  public function confirmReceive($id, Request $request)
{
    $order = Order::where('id_order', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    if ($order->status !== 'delivered') {
        return back()->with('error', 'Chỉ có thể xác nhận khi đơn hàng ở trạng thái ĐÃ GIAO.');
    }

    $order->status = 'received';
    $order->payment_status = 'paid';
    $order->save();

    return back()->with('success', 'Xác nhận nhận hàng thành công!');
}


}
