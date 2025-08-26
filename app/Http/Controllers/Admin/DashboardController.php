<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Parse date range from query parameters
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        if ($startDate && $endDate) {
            $start = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $end   = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        } else {
            // Default to today if no range selected
            $start = Carbon::now()->startOfDay();
            $end   = Carbon::now()->endOfDay();
        }

        // Orders within the selected range
        $ordersInRange = Order::whereBetween('created_at', [$start, $end]);

        // Tổng doanh thu trong khoảng
        $dailyRevenue = (clone $ordersInRange)->sum('total_amount');

        // Tổng số sản phẩm (không phụ thuộc thời gian)
        $totalProducts = Product::count();

        // Tổng số người dùng (không phụ thuộc thời gian)
        $totalUsers = User::count();

        // Tổng đơn hàng trong khoảng
        $totalOrdersToday = (clone $ordersInRange)->count();

        // Tổng khách hàng đăng ký trong khoảng
        $newUsersToday = User::whereBetween('created_at', [$start, $end])->count();

        // Tổng doanh thu tháng hiện tại (hoặc theo tháng của ngày bắt đầu)
        $monthlyRevenue = Order::whereYear('created_at', $start->year)
            ->whereMonth('created_at', $start->month)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Top 5 khách hàng mua nhiều nhất trong phạm vi lọc
        $topCustomers = User::join('orders', 'users.id_user', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select('users.id_user', 'users.name', DB::raw('SUM(orders.total_amount) as total_spent'))
            ->groupBy('users.id_user', 'users.name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // Top 5 sản phẩm bán chạy trong phạm vi lọc
        $topProducts = DB::table('order_items')
            ->join('variant', 'order_items.variant_id', '=', 'variant.id_variant')
            ->join('products', 'variant.product_id', '=', 'products.id_product')
            ->join('orders', 'order_items.order_id', '=', 'orders.id_order')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                'products.id_product',
                'products.name_product',
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->groupBy('products.id_product', 'products.name_product', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Top 5 đơn hàng mới nhất trong phạm vi lọc
        $latestOrders = Order::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Dữ liệu biểu đồ doanh thu theo ngày trong phạm vi lọc
        $period = CarbonPeriod::create($start, $end);
        $chartData    = [];
        $chartLabels  = [];

        foreach ($period as $date) {
            $revenueDay = Order::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
            $chartData[]   = $revenueDay;
            $chartLabels[] = $date->format('d/m');
        }

        return view('admin.dashboard', compact(
            'dailyRevenue',
            'totalProducts',
            'monthlyRevenue',
            'totalUsers',
            'totalOrdersToday',
            'newUsersToday',
            'topCustomers',
            'topProducts',
            'latestOrders',
            'chartData',
            'chartLabels',
            'startDate',
            'endDate'
        ));
    }
}

