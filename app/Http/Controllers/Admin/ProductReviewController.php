<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */


public function index(Request $request)
{
    $query = ProductReview::query()->with(['user', 'product']);

    // Nếu không có ngày được chọn, mặc định lấy ngày hôm nay
    $date = Carbon::today()->toDateString();

    $query->whereDate('created_at', $request->date);

    // Lọc theo trạng thái nếu có
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Lọc theo keyword nếu có
    if ($request->filled('keyword')) {
        $keyword = $request->keyword;

        $query->where(function ($q) use ($keyword) {
            $q->whereHas('user', function ($qUser) use ($keyword) {
                $qUser->where('name', 'like', "%$keyword%");
            })->orWhere('order_id', 'like', "%$keyword%");
        });
    }

    $reviews = $query->latest()->paginate(10)->withQueryString();

    return view('admin.product_reviews.index', compact('reviews', 'date'));
}





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductReview $productReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductReview $productReview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(Request $request)
    {
        Log::info('DỮ LIỆU AJAX:', $request->all());

        $request->validate([
            'id' => 'required|exists:product_reviews,id_review',
            'status' => 'required|in:pending,visible,hidden',
        ]);

        $review = ProductReview::where('id_review', $request->id)->firstOrFail();
        $review->status = $request->status;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái đã được cập nhật thành công.',
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id_review)
    {
        $review = ProductReview::findOrFail($id_review);
        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Đánh giá đã được xóa thành công.');
    }

}
