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
        // Determine filter type and date range
        $filterType = $request->query('filter_type'); // day|month|year|range|null
        $start = null;
        $end = null;
        $isFiltered = false;

        switch ($filterType) {
            case 'day':
                $date = $request->query('date');
                if ($date) {
                    $start = Carbon::parse($date)->startOfDay();
                    $end = Carbon::parse($date)->endOfDay();
                    $isFiltered = true;
                }
                break;
            case 'month':
                $month = $request->query('month'); // expects YYYY-MM
                if ($month) {
                    $start = Carbon::parse($month . '-01')->startOfMonth();
                    $end = Carbon::parse($month . '-01')->endOfMonth();
                    $isFiltered = true;
                }
                break;
            case 'year':
                $year = (int) $request->query('year');
                if ($year) {
                    $start = Carbon::create($year, 1, 1)->startOfYear();
                    $end = Carbon::create($year, 12, 31)->endOfYear();
                    $isFiltered = true;
                }
                break;
            case 'range':
                $startDate = $request->query('start_date');
                $endDate = $request->query('end_date');
                if ($startDate && $endDate) {
                    $start = Carbon::parse($startDate)->startOfDay();
                    $end = Carbon::parse($endDate)->endOfDay();
                    if ($start->lte($end)) {
                        $isFiltered = true;
                    }
                }
                break;
        }

        // Defaults when no filter provided: today and current month
        if (!$isFiltered) {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }

        // Build a human readable period label
        if ($filterType === 'day' && $isFiltered) {
            $periodLabel = 'ngày ' . $start->format('d/m/Y');
        } elseif ($filterType === 'month' && $isFiltered) {
            $periodLabel = 'tháng ' . $start->format('m/Y');
        } elseif ($filterType === 'year' && $isFiltered) {
            $periodLabel = 'năm ' . $start->format('Y');
        } elseif ($filterType === 'range' && $isFiltered) {
            $periodLabel = $start->format('d/m/Y') . ' - ' . $end->format('d/m/Y');
        } else {
            $periodLabel = 'tháng ' . now()->format('m/Y');
        }

        // Revenue (within selected period)
        $periodRevenue = Order::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->sum('total_amount');

        // Total products (overall)
        $totalProducts = Product::count();

        // Total users (overall)
        $totalUsers = User::count();

        // Orders count within period
        $ordersInPeriod = Order::whereBetween('created_at', [$start, $end])->count();

        // New users within period
        $newUsersInPeriod = User::whereBetween('created_at', [$start, $end])->count();

        // Top 5 customers by spend within period
        $topCustomers = User::join('orders', 'users.id_user', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
            ->select('users.id_user', 'users.name', 'users.email', DB::raw('SUM(orders.total_amount) as total_spent'))
            ->groupBy('users.id_user', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // Top 5 products by quantity sold within period (based on orders in range)
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id_order')
            ->join('variant', 'order_items.variant_id', '=', 'variant.id_variant')
            ->join('products', 'variant.product_id', '=', 'products.id_product')
            ->whereBetween('orders.created_at', [$start, $end])
            ->where('orders.status', 'completed')
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

        // Latest 5 orders within period
        $latestOrders = Order::with('user')
            ->whereBetween('created_at', [$start, $end])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Build daily revenue series for the selected period
        $daysDiff = $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay()) + 1;
        $dailyRevenueMap = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as day_date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->groupBy('day_date')
            ->orderBy('day_date')
            ->pluck('revenue', 'day_date');

        $chartLabels = [];
        $chartData = [];
        for ($i = 0; $i < $daysDiff; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->toDateString();
            $chartLabels[] = $date->format('d/m');
            $chartData[] = (int) ($dailyRevenueMap[$key] ?? 0);
        }

        return view('admin.dashboard', compact(
            'periodRevenue',
            'totalProducts',
            'totalUsers',
            'ordersInPeriod',
            'newUsersInPeriod',
            'topCustomers',
            'topProducts',
            'latestOrders',
            'chartData',
            'chartLabels',
            'periodLabel',
            'isFiltered',
            'filterType'
        ));
    }
}

