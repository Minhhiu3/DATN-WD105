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

class DashboardController extends Controller
{
    public function index(Request $request)
    {

   $month = $request->input('month');
   if (empty($month)) {
    $month = now()->format('Y-m');
}



    [$year, $monthNumber] = explode('-', $month);

        $today = Carbon::createFromFormat('d/m/Y', now()->format('d/m/Y'));

        // Tổng doanh thu hôm nay
        $dailyRevenue = Order::whereDate('created_at', $today)->sum('total_amount');

        // Tổng số sản phẩm
        $totalProducts = Product::count();

        // Tổng số người dùng
        $totalUsers = User::count();

        // Tổng đơn hôm nay
        $totalOrdersToday = Order::whereDate('created_at', $today)->count();

        // Tổng khách hàng đăng ký hôm nay
        $newUsersToday = User::whereDate('created_at', $today)->count();
         // Tổng doanh thu tháng hiện tại
         $monthlyRevenue = Order::whereYear('created_at', $year)
        ->whereMonth('created_at', $monthNumber)
        ->where('status', 'completed')
        ->sum('total_amount');

        // Top 5 khách hàng mua nhiều nhất (theo tổng tiền)
        $topCustomers = User::join('orders', 'users.id_user', '=', 'orders.user_id')
            ->select('users.id_user', 'users.name', DB::raw('SUM(orders.total_amount) as total_spent'))
            ->groupBy('users.id_user', 'users.name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // Top 5 sản phẩm bán chạy (theo số lượng)
        $topProducts = DB::table('order_items')
            ->join('variant', 'order_items.variant_id', '=', 'variant.id_variant')
            ->join('products', 'variant.product_id', '=', 'products.id_product')
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

        // Top 5 đơn hàng mới nhất
        $latestOrders = Order::with('user')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // $currentMonth = Carbon::now()->month; // Tháng hiện tại
        // $currentYear = Carbon::now()->year;   // Năm hiện tại

        // Lấy doanh thu từng ngày trong tháng hiện tại
        $dailyRevenueMonth = DB::table('orders')
            ->select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereMonth('created_at', $monthNumber)
            ->whereYear('created_at', $year)
            ->where('status', 'completed') // chỉ tính đơn hoàn thành
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('revenue', 'day'); // key = day, value = revenue

        // Tạo mảng đủ số ngày trong tháng, nếu ngày nào không có đơn thì gán = 0
        $daysInMonth = Carbon::now()->daysInMonth;
        $chartData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $chartData[] = $dailyRevenueMonth[$i] ?? 0;
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
            'month'
        ));
    }
}

