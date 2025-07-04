<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(8)->get(); // Lấy 8 sản phẩm mới nhất
        $banners = Banner::all();
        return view('client.pages.home', compact('products', 'banners'));
    }

}
