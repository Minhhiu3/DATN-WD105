<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Order_item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Lấy ngày lọc (mặc định hôm nay)
        $date = $request->input('date') ?? Carbon::today()->toDateString();
        $query->whereDate('created_at', $date);

        // Lấy mã đơn (id_order) từ query string, mặc định null
        $code = $request->input('code');

        if ($code) {
            $query->where('id_order', $code);
        }

        $orders = $query->orderBy('created_at', 'desc')
                        ->paginate(10)
                        ->appends([
                            'date' => $date,
                            'code' => $code,
                        ]);

        // Bây giờ $code đã luôn tồn tại, sẽ không còn lỗi undefined
        return view('admin.orders.index', compact('orders', 'date', 'code'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($order_id)
    {

        $order = Order::findOrFail($order_id);
        $user = User::findOrFail($order->user_id);
        $order_items = Order_item::with(['variant.size', 'variant.product'])
            ->where('order_id', $order_id)
            ->get();


        return view('admin.orders.show', compact('user', 'order_items'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_order)
    {
        // Lấy đơn hàng theo ID, kèm thông tin user
        $order = Order::with('user')->where('id_order', $id_order)->firstOrFail();

        return view('admin.orders.edit', compact('order'));
    }


    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $order_id)
{
    $request->validate([
        'status' => 'required|in:pending,processing,shipping,completed,canceled',
    ]);

    // Lấy đơn hàng theo ID
    $order = Order::findOrFail($order_id);

    // So sánh và cập nhật nếu cần
    if ($order->status !== $request->status) {
        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    } else {
        return redirect()->route('admin.orders.index')
            ->with('info', 'Trạng thái không thay đổi.');
    }
}

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id_order',
            'status' => 'required|in:pending,processing,shipping,completed,canceled',
        ]);

        $order = Order::findOrFail($request->id);
        $order->status = $request->status;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Trạng thái đã cập nhật']);
    }
    public function cancel(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id_order',
        ]);

        $order = Order::findOrFail($request->id);

        if ($order->status !== 'canceled') {
            $order->status = 'canceled';
            $order->save();
            return response()->json(['success' => true, 'message' => 'Đã hủy đơn hàng!']);
        }

        return response()->json(['success' => false, 'message' => 'Đơn hàng đã bị hủy trước đó.']);
    }


 
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
