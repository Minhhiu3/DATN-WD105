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
        // Get date range from request or set defaults
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        
        // Convert to Carbon instances for easier manipulation
        $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        
        // Today for comparison
        $today = Carbon::today();

        // Tổng doanh thu trong khoảng thời gian được chọn
        $dailyRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');

        // Tổng số sản phẩm (không phụ thuộc vào thời gian)
        $totalProducts = Product::count();

        // Tổng số người dùng (không phụ thuộc vào thời gian)
        $totalUsers = User::count();

        // Tổng đơn hàng trong khoảng thời gian được chọn
        $totalOrdersToday = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        // Tổng khách hàng đăng ký trong khoảng thời gian được chọn
        $newUsersToday = User::whereBetween('created_at', [$startDate, $endDate])->count();

        // Tổng doanh thu trong khoảng thời gian (same as dailyRevenue but keeping for consistency)
        $monthlyRevenue = $dailyRevenue;

        // Top 5 khách hàng mua nhiều nhất trong khoảng thời gian được chọn
        $topCustomers = User::join('orders', 'users.id_user', '=', 'orders.user_id')
            ->select('users.id_user', 'users.name', 'users.email', DB::raw('SUM(orders.total_amount) as total_spent'))
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->groupBy('users.id_user', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // Top 5 sản phẩm bán chạy trong khoảng thời gian được chọn
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id_order')
            ->join('variant', 'order_items.variant_id', '=', 'variant.id_variant')
            ->join('products', 'variant.product_id', '=', 'products.id_product')
            ->select(
                'products.id_product',
                'products.name_product',
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_sold')
            )
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', 'completed')
            ->groupBy('products.id_product', 'products.name_product', 'products.image')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Đơn hàng mới nhất trong khoảng thời gian được chọn
        $latestOrders = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Chart data based on selected period
        $chartData = $this->getChartData($startDate, $endDate);

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
            'startDate',
            'endDate'
        ));
    }

    private function getChartData($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        
        // Calculate the difference in days
        $diffInDays = $startDate->diffInDays($endDate) + 1;
        
        if ($diffInDays <= 31) {
            // Daily chart for periods <= 31 days
            return $this->getDailyChartData($startDate, $endDate);
        } elseif ($diffInDays <= 365) {
            // Monthly chart for periods <= 1 year
            return $this->getMonthlyChartData($startDate, $endDate);
        } else {
            // Yearly chart for periods > 1 year
            return $this->getYearlyChartData($startDate, $endDate);
        }
    }

    private function getDailyChartData($startDate, $endDate)
    {
        $dailyRevenue = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('revenue', 'date');

        $chartData = [];
        $labels = [];
        $current = Carbon::parse($startDate);
        
        while ($current <= $endDate) {
            $dateKey = $current->format('Y-m-d');
            $chartData[] = $dailyRevenue[$dateKey] ?? 0;
            $labels[] = $current->format('d/m');
            $current->addDay();
        }

        return [
            'data' => $chartData,
            'labels' => $labels,
            'type' => 'daily'
        ];
    }

    private function getMonthlyChartData($startDate, $endDate)
    {
        $monthlyRevenue = DB::table('orders')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });

        $chartData = [];
        $labels = [];
        $current = Carbon::parse($startDate)->startOfMonth();
        
        while ($current <= $endDate) {
            $monthKey = $current->format('Y-m');
            $chartData[] = $monthlyRevenue[$monthKey]->revenue ?? 0;
            $labels[] = $current->format('m/Y');
            $current->addMonth();
        }

        return [
            'data' => $chartData,
            'labels' => $labels,
            'type' => 'monthly'
        ];
    }

    private function getYearlyChartData($startDate, $endDate)
    {
        $yearlyRevenue = DB::table('orders')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('revenue', 'year');

        $chartData = [];
        $labels = [];
        $startYear = Carbon::parse($startDate)->year;
        $endYear = Carbon::parse($endDate)->year;
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $chartData[] = $yearlyRevenue[$year] ?? 0;
            $labels[] = $year;
        }

        return [
            'data' => $chartData,
            'labels' => $labels,
            'type' => 'yearly'
        ];
    }
}

