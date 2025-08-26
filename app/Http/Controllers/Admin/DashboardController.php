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
        $monthlyRevenue = Order::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('status', 'completed') // chỉ tính đơn đã hoàn thành
            ->sum('total_amount');

        // ============== Bộ lọc thời gian ==============
        $filterType = $request->query('filter_type', 'month'); // day | month | year | range
        $startDate = null;
        $endDate = null;
        $filterTitle = '';
        $xAxisTitle = '';

        switch ($filterType) {
            case 'day':
                $dateStr = $request->query('date', now()->toDateString());
                $day = Carbon::parse($dateStr);
                $startDate = $day->copy()->startOfDay();
                $endDate = $day->copy()->endOfDay();
                $filterTitle = 'Ngày ' . $day->format('d/m/Y');
                $xAxisTitle = 'Giờ trong ngày';
                break;
            case 'year':
                $year = (int) $request->query('year', now()->year);
                $startDate = Carbon::create($year, 1, 1, 0, 0, 0);
                $endDate = Carbon::create($year, 12, 31, 23, 59, 59);
                $filterTitle = 'Năm ' . $year;
                $xAxisTitle = 'Tháng trong năm';
                break;
            case 'range':
                $startStr = $request->query('start_date');
                $endStr = $request->query('end_date');
                if (!$startStr || !$endStr) {
                    $startDate = now()->copy()->subDays(6)->startOfDay();
                    $endDate = now()->copy()->endOfDay();
                } else {
                    $startDate = Carbon::parse($startStr)->startOfDay();
                    $endDate = Carbon::parse($endStr)->endOfDay();
                }
                $filterTitle = 'Từ ' . $startDate->format('d/m/Y') . ' đến ' . $endDate->format('d/m/Y');
                $xAxisTitle = 'Ngày';
                break;
            case 'month':
            default:
                $monthStr = $request->query('month', now()->format('Y-m'));
                $month = Carbon::createFromFormat('Y-m', $monthStr);
                $startDate = $month->copy()->startOfMonth();
                $endDate = $month->copy()->endOfMonth();
                $filterTitle = 'Tháng ' . $month->format('m/Y');
                $xAxisTitle = 'Ngày trong tháng';
                break;
        }

        // Top 5 khách hàng mua nhiều nhất (theo tổng tiền) trong khoảng lọc
        $topCustomers = User::join('orders', 'users.id_user', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('users.id_user', 'users.name', 'users.email', DB::raw('SUM(orders.total_amount) as total_spent'))
            ->groupBy('users.id_user', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // Top 5 sản phẩm bán chạy (theo số lượng) trong khoảng lọc
        $topProducts = DB::table('order_items')
            ->join('variant', 'order_items.variant_id', '=', 'variant.id_variant')
            ->join('products', 'variant.product_id', '=', 'products.id_product')
            ->whereBetween('order_items.created_at', [$startDate, $endDate])
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

        // Top 5 đơn hàng mới nhất trong khoảng lọc
        $latestOrders = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Biểu đồ doanh thu theo khoảng lọc
        $chartLabels = [];
        $chartData = [];

        if ($filterType === 'year') {
            $revenueByMonth = DB::table('orders')
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('revenue', 'month');

            for ($m = 1; $m <= 12; $m++) {
                $chartLabels[] = 'Tháng ' . $m;
                $chartData[] = (float) ($revenueByMonth[$m] ?? 0);
            }
        } elseif ($filterType === 'day') {
            $revenueByHour = DB::table('orders')
                ->select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->groupBy('hour')
                ->orderBy('hour')
                ->pluck('revenue', 'hour');

            for ($h = 0; $h < 24; $h++) {
                $chartLabels[] = 'Giờ ' . $h;
                $chartData[] = (float) ($revenueByHour[$h] ?? 0);
            }
        } else {
            // month hoặc range -> theo ngày
            $revenueByDay = DB::table('orders')
                ->select(
                    DB::raw('DATE(created_at) as day'),
                    DB::raw('SUM(total_amount) as revenue')
                )
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('day')
                ->pluck('revenue', 'day');

            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                $chartLabels[] = $cursor->format('d/m');
                $key = $cursor->format('Y-m-d');
                $chartData[] = (float) ($revenueByDay[$key] ?? 0);
                $cursor->addDay();
            }
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
            'filterType',
            'filterTitle',
            'xAxisTitle'
        ));
    }
}

