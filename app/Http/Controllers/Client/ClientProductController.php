<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ClientProductController extends Controller
{
    public function index()
    {
        $sizes=Size::all();
        $categories=Category::all();
        $products = Product::with(['category', 'albumProducts'])->latest()->paginate(9);
        return view('client.pages.products', compact('products','categories','sizes'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id); // Tìm sản phẩm theo ID
        return view('client.pages.product-detail', compact('product'));
    }
    public function search(Request $request)
    {
        // Validate dữ liệu tìm kiếm
        $request->validate([
            'query' => 'required|string|max:255',
        ]);

        // Tìm kiếm sản phẩm theo tên
        $products = Product::where('name', 'like', '%' . $request->query . '%')
            ->with('category')
            ->latest()
            ->paginate(10);

        // Trả về view với kết quả tìm kiếm
        return view('admin.products.index', compact('products'));
    }
}
