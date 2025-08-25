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
    ],[
        'product_id.exists' => 'Sản phẩm không tồn tại.',
        'order_id.exists' => 'Đơn hàng không tồn tại.',
        'rating.required' => 'Vui lòng chọn đánh giá từ 1 đến 5 sao.',
        'rating.integer' => 'Đánh giá phải là một số nguyên.',
        'rating.min' => 'Đánh giá tối thiểu là 1 sao.',
        'rating.max' => 'Đánh giá tối đa là 5 sao.',
        'comment.max' => 'Bình luận không được vượt quá 1000 ký tự.',
    ]);


    $order = Order::where('id_order', $request->order_id)
        ->where('user_id', auth()->id())
        ->first();

    if (!$order || $order->status !== 'completed') {
        return redirect()->back()->withErrors('Bạn chỉ có thể đánh giá sau khi đơn hàng hoàn thành.');
    }


    $exists = ProductReview::where('user_id', auth()->id())
        ->where('product_id', $request->product_id)
        ->where('order_id', $request->order_id)
        ->exists();

    if ($exists) {
        return redirect()->back()->withErrors('Bạn đã đánh giá sản phẩm này cho đơn hàng này.');
    }


    $review = new ProductReview();
    $review->user_id = auth()->id();
    $review->product_id = $request->input('product_id');
    $review->order_id = $request->input('order_id');
    $review->rating = $request->input('rating');
    $review->comment = $request->input('comment');
     $review->status = 'visible';
    $review->save();

    return redirect()->route('client.product.show',['id' => $review->product_id])->with('success', 'Đánh giá sản phẩm thành công');
}
    public function index()
    {

        $reviews = ProductReview::where('user_id', auth()->id())->get();

        return view('client.pages.product-reviews', compact('reviews'));
    }
    public function show($id)
    {

        $review = ProductReview::findOrFail($id);

        return view('client.pages.product-review-detail', compact('review'));

    }
}
