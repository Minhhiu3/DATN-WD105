<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index()
    {
        $cart = session()->get('cart', []);

        // Tính tổng tiền (nếu cần)
        $total = collect($cart)->reduce(function ($sum, $item) {
            return $sum + $item['price'] * $item['quantity'];
        }, 0);
          dd($cart);
        return view('cart.index', compact('cart', 'total'));
    }

    // Thêm sản phẩm vào giỏ hàng
   public function addAjax(Request $request)
{
    $product = Product::findOrFail($request->product_id);
    $quantity = (int) $request->input('quantity', 1);

    $cart = session()->get('cart', []);

    if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $quantity;
    } else {
        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'thumbnail' => $product->thumbnail,
            'quantity' => $quantity,
        ];
    }

    session()->put('cart', $cart);

    // Trả HTML của mini-cart về để cập nhật
    $view = view('partials.header_home', ['cart' => $cart])->render();
    return response()->json([
        'success' => true,
        'html' => $view,
        'count' => collect($cart)->sum('quantity')
    ]);
}




    // Cập nhật số lượng
    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity', 1);

        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = max(1, $quantity); // tránh số lượng < 1
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', '✅ Đã cập nhật số lượng sản phẩm.');
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', '🗑️ Đã xoá sản phẩm khỏi giỏ hàng.');
    }
}
