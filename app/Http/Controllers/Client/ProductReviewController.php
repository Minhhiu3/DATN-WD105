<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    //
public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id_product',
        'order_id' => 'required|exists:orders,id_order',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    // Kiểm tra đơn hàng đã hoàn thành chưa
    $order = Order::where('id_order', $request->order_id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$order || $order->status !== 'completed') {
        return redirect()->back()->withErrors('Bạn chỉ có thể đánh giá sau khi đơn hàng hoàn thành.');
    }

    // Kiểm tra đã đánh giá sản phẩm này trong đơn hàng này chưa
    $exists = ProductReview::where('user_id', auth()->id())
        ->where('product_id', $request->product_id)
        ->where('order_id', $request->order_id)
        ->exists();

    if ($exists) {
        return redirect()->back()->withErrors('Bạn đã đánh giá sản phẩm này cho đơn hàng này.');
    }

    // Lưu đánh giá
    $review = new ProductReview();
    $review->user_id = auth()->id();
    $review->product_id = $request->input('product_id');
    $review->order_id = $request->input('order_id');
    $review->rating = $request->input('rating');
    $review->comment = $request->input('comment');
    $review->save();

    return redirect()->route('products')->with('success', 'Đánh giá của bạn đã được gửi!');
}
    public function index()
    {
        // Fetch all reviews for the authenticated user
        $reviews = ProductReview::where('user_id', auth()->id())->get();

        return view('client.pages.product-reviews', compact('reviews'));
    }
    public function show($id)
    {
        // Fetch a specific review by ID
        $review = ProductReview::findOrFail($id);

        return view('client.pages.product-review-detail', compact('review'));

    }
}
