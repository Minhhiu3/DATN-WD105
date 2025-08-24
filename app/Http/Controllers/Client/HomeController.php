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

        // Top 5 sản phẩm bán chạy (theo số lượng)
        $topProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('variant', 'products.id_product', '=', 'variant.product_id')
            ->join('order_items', 'variant.id_variant', '=', 'order_items.variant_id')
            ->groupBy('products.id_product')
            ->orderByDesc('total_sold')
            ->with(['variants', 'advice_product']) 
            ->limit(8)
            ->get();
        return view('client.pages.home', compact('products', 'banners','topProducts'));
    }

}
