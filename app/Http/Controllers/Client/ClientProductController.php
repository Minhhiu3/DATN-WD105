<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ClientProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->take(8)->get(); // Lấy 8 sản phẩm mới nhất
        return view('client.pages.home', compact('products'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id); // Tìm sản phẩm theo ID
        return view('client.pages.product-detail', compact('product'));
    }
}