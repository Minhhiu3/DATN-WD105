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
        // Determine selected date range (defaults to current month)
        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');

        $defaultStart = Carbon::now()->startOfMonth();
        $defaultEnd = Carbon::now()->endOfDay();

        $startDate = $startDateInput ? Carbon::parse($startDateInput)->startOfDay() : $defaultStart;
        $endDate = $endDateInput ? Carbon::parse($endDateInput)->endOfDay() : $defaultEnd;

        if ($endDate->lt($startDate)) {
            [$startDate, $endDate] = [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        $isFiltered = $startDate->ne($defaultStart) || $endDate->ne($defaultEnd);

        // Metrics within selected range
        $revenueInRange = Order::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');

        $totalProducts = Product::count();
        $totalUsers = User::count();

        $ordersInRange = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $newUsersInRange = User::whereBetween('created_at', [$startDate, $endDate])->count();

        // Monthly revenue for the month of end date (still shown as a separate card)
        $monthlyRevenue = Order::whereYear('created_at', $endDate->year)
            ->whereMonth('created_at', $endDate->month)
            ->where('status', 'completed')
            ->sum('total_amount');

        // Top 5 customers by total spent within range
        $topCustomers = User::join('orders', 'users.id_user', '=', 'orders.user_id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->select('users.id_user', 'users.name', DB::raw('SUM(orders.total_amount) as total_spent'))
            ->groupBy('users.id_user', 'users.name')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        // Top 5 best-selling products within range (by quantity)
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id_order')
            ->join('variant', 'order_items.variant_id', '=', 'variant.id_variant')
            ->join('products', 'variant.product_id', '=', 'products.id_product')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
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

        // Latest 5 orders within range
        $latestOrders = Order::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Build daily revenue data within selected range
        $dailyRevenueRows = DB::table('orders')
            ->select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('revenue', 'day');

        $chartData = [];
        $chartLabels = [];
        $cursor = $startDate->copy()->startOfDay();
        while ($cursor->lte($endDate)) {
            $key = $cursor->toDateString();
            $chartData[] = (float) ($dailyRevenueRows[$key] ?? 0);
            $chartLabels[] = $cursor->format('d/m');
            $cursor->addDay();
        }

        // Map to original view variable names for minimal blade changes
        $dailyRevenue = $revenueInRange;
        $totalOrdersToday = $ordersInRange;
        $newUsersToday = $newUsersInRange;

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
            'endDate',
            'isFiltered'
        ));
    }
}

