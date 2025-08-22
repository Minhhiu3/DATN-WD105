<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
        use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::where('visibility', 'visible')->latest()->take(8)->get(); // Lấy 8 sản phẩm mới nhất
        $banners = Banner::all();


// $bestSellingProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
//     ->join('variant', 'products.id_product', '=', 'variant.product_id')
//     ->join('order_items', 'variant.id_variant', '=', 'order_items.variant_id')
//     ->join('orders', 'order_items.order_id', '=', 'orders.id_order')
//     ->where('orders.status', 'completed') // chỉ tính đơn đã hoàn thành
//     ->groupBy('products.id_product')
//     ->orderByDesc('total_sold')
//     ->take(8)
//     ->get();
//     dd($bestSellingProducts);
        return view('client.pages.home', compact('products', 'banners'));
    }

}
