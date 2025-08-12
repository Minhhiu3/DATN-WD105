<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientProductController extends Controller
{
    public function index(Request $request)
    {

        $sizes = Size::all();
        $categories = Category::all();
        $keyword = $request->input('keyword');
          $category = $request->input('category');
    $size = $request->input('size');
     $products = Product::where('visibility', 'visible')->with(['category', 'albumProducts'])
        ->when($keyword, function ($query, $keyword) {
            $query->where('name_product', 'like', "%$keyword%");
        })
        ->when($category, function ($query, $category) {
            $query->where('category_id', $category);
        })
       ->when($size, function ($query, $size) {
    $query->whereHas('variants.size', function ($q) use ($size) {
        $q->where('name', $size);
    });
})


        ->latest()->paginate(9);
        return view('client.pages.products', compact('products', 'categories', 'sizes','keyword'));    }

  public function show($id)
{
    $product = Product::with(
        'category',
        'variants.color',
        'variants.size',

         'albumProducts',
            'productReviews.user')->findOrFail($id);

    $user = Auth::user();
    $canReview = false;
    $orderId = null;
      $alreadyReviewed = false;
      $averageRating = $product->productReviews()->avg('rating');


    if ($user) {
         $alreadyReviewed = ProductReview::where('user_id', $user->id_user)
            ->where('product_id', $id)
            ->exists();
        // Tìm đơn hàng đã hoàn thành chứa sản phẩm này, chưa đánh giá
         if (! $alreadyReviewed) {
        $order = Order::where('user_id', $user->id_user)
            ->where('status', 'completed')
            ->whereHas('orderItems.variant', function ($q) use ($id) {
                $q->where('product_id', $id);
            })
            ->whereDoesntHave('productReviews', function ($q) use ($user, $id) {
                $q->where('user_id', $user->id_user)
                  ->where('product_id', $id);
            })
            ->first();

        if ($order) {
            $canReview = true;
            $orderId = $order->id_order;
        }
    }
    }
    return view('client.pages.product-detail', compact('product', 'canReview', 'orderId', 'alreadyReviewed', 'averageRating'));
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

    public function filterByPrice(Request $request)
    {
        $products = Product::query();

        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'under_500000':
                    $products->where('price', '<', 500000);
                    break;
                case '500000_2000000':
                    $products->whereBetween('price', [500000, 2000000]);
                    break;
                case 'over_2000000':
                    $products->where('price', '>', 2000000);
                    break;
            }
        }

        $products = $products->with(['category', 'albumProducts'])->latest()->paginate(9);

        // lấy thêm dữ liệu danh mục và size nếu cần
        $sizes = Size::all();
        $categories = Category::all();

        return view('client.pages.products', compact('products', 'categories', 'sizes'));
    }


}
