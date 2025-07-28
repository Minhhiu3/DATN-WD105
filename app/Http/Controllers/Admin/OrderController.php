<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Order_item;
use App\Models\OrderItem;
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
            $query->where('order_code', $code);
        }
        $status = $request->input('status');

        if ($status) {
            $query->where('status', $status);
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
    // Lấy đơn hàng
    $order = Order::findOrFail($order_id);

    // Lấy thông tin người dùng
    $user = User::findOrFail($order->user_id);

    // Lấy danh sách sản phẩm trong đơn hàng
    $order_items = OrderItem::with(['variant.size','variant.color', 'variant.product'])
        ->where('order_id', $order_id)
        ->get();

    // Gửi dữ liệu sang view
    return view('admin.orders.show', compact('order', 'user', 'order_items'));
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
        'status' => 'required|in:pending,processing,shipping,delivered,received,completed,canceled',
    ]);

    // Lấy đơn hàng theo ID
    $order = Order::findOrFail($order_id);

    // Map mức độ trạng thái
    $statusLevels = [
        'pending' => 1,
                                    'processing' => 2,
                                    'shipping' => 3,
                                    'delivered' => 4,
                                    'received' => 5,
                                    'completed' => 6,
                                    'canceled' => 7,
    ];

    $currentLevel = $statusLevels[$order->status] ?? 0;
    $newLevel = $statusLevels[$request->status] ?? 0;

    // Kiểm tra nếu đơn hàng đã hoàn thành hoặc hủy thì không được đổi nữa
    if (in_array($order->status, ['completed', 'canceled'])) {
        return redirect()->route('admin.orders.index')
            ->with('error', 'Đơn hàng đã hoàn thành hoặc bị hủy, không thể thay đổi trạng thái.');
    }

    // Không cho nhảy bước (chỉ cho phép tăng 1 level)
    if ($newLevel > $currentLevel + 1) {
        return redirect()->route('admin.orders.index')
            ->with('error', 'Không được bỏ qua bước, hãy cập nhật tuần tự!');
    }

    // Không cho quay lại bước trước
    if ($newLevel < $currentLevel) {
        return redirect()->route('admin.orders.index')
            ->with('error', 'Không thể quay về trạng thái trước!');
    }

    // Cập nhật trạng thái nếu hợp lệ
    if ($order->status !== $request->status) {
        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    }

    return redirect()->route('admin.orders.index')
        ->with('info', 'Trạng thái không thay đổi.');
}



    public function updateStatus(Request $request)
{
    $request->validate([
        'id' => 'required|exists:orders,id_order',
        'status' => 'required|in:pending,processing,shipping,delivered,received,completed,canceled',
    ]);

    $order = Order::findOrFail($request->id);

    $statusLevels = [
        'pending' => 1,
        'processing' => 2,
        'shipping' => 3,
        'delivered' => 4,
        'received' => 5,
        'completed' => 6,
        'canceled' => 7,
    ];

    $currentLevel = $statusLevels[$order->status] ?? 0;
    $newLevel = $statusLevels[$request->status] ?? 0;

    if (in_array($order->status, ['completed', 'canceled'])) {
        return response()->json(['success' => false, 'message' => 'Đơn hàng đã hoàn thành hoặc bị hủy, không thể thay đổi trạng thái.']);
    }

    if ($newLevel > $currentLevel + 1) {
        return response()->json(['success' => false, 'message' => 'Không được bỏ qua bước, hãy cập nhật tuần tự!']);
    }

    if ($newLevel < $currentLevel) {
        return response()->json(['success' => false, 'message' => 'Không thể quay về trạng thái trước!']);
    }

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
