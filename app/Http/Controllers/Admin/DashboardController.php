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

        // Lấy ngày cụ thể từ request hoặc mặc định là hôm nay
        $day = $request->input('day');
        if (empty($day)) {
            $day = now()->format('Y-m-d');
        }
        
        // Tạo đối tượng Carbon từ ngày được chọn
        $selectedDate = Carbon::createFromFormat('Y-m-d', $day);

        $year = $selectedDate->year;
        $monthNumber = $selectedDate->month;
        
        // Doanh thu của ngày được chọn
        $dailyRevenue = Order::whereDate('created_at', $selectedDate)
            ->where('status', 'completed') // chỉ tính đơn đã hoàn thành
            ->sum('total_amount');

        // Tổng số đơn hàng của ngày được chọn
        $totalOrdersToday = Order::whereDate('created_at', $selectedDate)->count();

        // ==== THỐNG KÊ CHUNG ====
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', $selectedDate)->count();

        // Tổng doanh thu tháng hiện tại
        $monthlyRevenue = Order::whereYear('created_at', $year)
            ->whereMonth('created_at', $monthNumber)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Doanh thu riêng của ngày được chọn 
        $selectedDayRevenue = $dailyRevenue;

        // ==== TOP KHÁCH HÀNG ====
        $topCustomers = User::join('orders', 'users.id_user', '=', 'orders.user_id')
            ->select('users.id_user', 'users.name', 'users.email', DB::raw('SUM(orders.total_amount) as total_spent'))
            ->whereYear('orders.created_at', $year)
            ->whereMonth('orders.created_at', $monthNumber)
            ->where('orders.status', 'completed')
            ->groupBy('users.id_user', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // ==== TOP SẢN PHẨM ====
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

        // ==== DOANH THU TỪNG NGÀY TRONG THÁNG ====
        $dailyRevenueMonth = DB::table('orders')
            ->select(
                DB::raw('DAY(created_at) as day'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereMonth('created_at', $monthNumber)
            ->whereYear('created_at', $year)
            ->where('status', 'completed')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('revenue', 'day');

        $daysInMonth = Carbon::createFromDate($year, $monthNumber)->daysInMonth;
        $chartData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $chartData[] = $dailyRevenueMonth[$i] ?? 0;
        }

        return view('admin.dashboard', compact(
            'dailyRevenue',          // doanh thu của ngày được chọn
            'totalProducts',
            'monthlyRevenue',
            'totalUsers',
            'totalOrdersToday',      // đơn hàng của ngày được chọn
            'newUsersToday',
            'topCustomers',
            'topProducts',
            'latestOrders',
            'chartData',
            'month',
            'day',
            'selectedDayRevenue'
        ));
    }
}

