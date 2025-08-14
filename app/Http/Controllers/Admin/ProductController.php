<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\AlbumProduct;
use App\Models\AdviceProduct;
use App\Models\Brand;
use App\Models\Variant;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Lấy tất cả danh mục để đổ vào dropdown
        $categoris = Category::all();

        // Query builder để lọc sản phẩm
        $productsQuery = Product::with(['category', 'advice_product','brand'])->withSum('variants', 'quantity');



        // Lọc theo từ khóa
        if ($request->filled('keyword')) {
            $productsQuery->where('name_product', 'like', '%' . $request->keyword . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $productsQuery->where('category_id', $request->category);
        }
        // Lọc theo danh mục
        if ($request->filled('brand')) {
            $productsQuery->where('brand_id', $request->brand);
        }
        // Phân trang kết quả
        $products = $productsQuery->latest()->paginate(10);



        return view('admin.products.index', compact('products', 'categoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories= Category::all();
        $brands= Brand::all();

        // Trả về view để tạo sản phẩm mới
          return view('admin.products.create', compact('categories','brands'));
    }
    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // ✅ 1. Validate dữ liệu
    $request->validate([
        'name_product'   => 'required|string|max:255',
        'price'          => 'required|numeric|min:0',
        'category_id'    => 'required|exists:category,id_category', // Sửa đúng bảng categories
        'brand_id'    => 'required|exists:brands,id_brand', 
        'description'    => 'nullable|string',
        'image'          => 'required|image|mimes:jpeg,jpg,png|max:2048',
        'album.*'        => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // validate từng ảnh album

        // ✅ Validate dữ liệu advice_product
        // 'value'          => 'required|integer|min:1|max:99', // chỉ cho phép từ 1 đến 99 (%)
        // 'start_date'     => 'required|date',
        // 'end_date'       => 'required|date|after_or_equal:start_date',
                'start_date.required' => 'Vui lòng chọn ngày bắt đầu.',
        'end_date.required'   => 'Vui lòng chọn ngày kết thúc.',
        'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu',
        'value.required' => 'Vui lòng nhập phần trăm giảm giá.',
        'value.integer'  => 'Phần trăm giảm giá phải là số nguyên.',
        'value.min'      => 'Phần trăm giảm giá phải lớn hơn 0%.',
        'value.max'      => 'Phần trăm giảm giá phải nhỏ hơn 100%.',
                'status'         => 'required|in:on,off', // Enum chỉ chấp nhận on/off
    ], [

    ]);

    // ✅ 2. Chuẩn bị dữ liệu
    $data = $request->only(['name_product', 'price', 'category_id', 'brand_id', 'description']);

    // ✅ 3. Upload ảnh chính
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('public/products');
        $data['image'] = str_replace('public/', '', $path);
    } else {
        return back()->with('error', 'Không tìm thấy file ảnh chính.');
    }

    // ✅ 4. Tạo sản phẩm
    $product = Product::create($data);
    $product->refresh(); // ép Laravel reload từ DB để có id_product

    // ✅ 5. Lưu album ảnh (nếu có)
    if ($request->hasFile('album')) {
        foreach ($request->file('album') as $albumImage) {
            $albumPath  = $albumImage->store('public/products/album');
            $cleanPath  = str_replace('public/', '', $albumPath);

            AlbumProduct::create([
                'product_id' => $product->id_product,
                'image'      => $cleanPath,
            ]);
        }
    }

    // ✅ 6. Tạo dữ liệu advice_product liên kết
    AdviceProduct::create([
        'product_id' => $product->id_product,
        'value'      => $request->input('value'),
        'start_date' => $request->input('start_date'),
        'end_date'   => $request->input('end_date'),
        'status'     => $request->input('status'),
    ]);

    // ✅ 7. Chuyển hướng về danh sách
    return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm và dữ liệu khuyến mãi thành công.');
}




    /**
     * Display the specified resource.
     */
public function show(Product $product)
{
    // Lấy tất cả categories
    $categories = Category::all();

    // Lấy tất cả ảnh album của sản phẩm
    $album_products = AlbumProduct::where('product_id', $product->id_product)->get();

    // ✅ Tổng số sản phẩm còn trong kho
    $total_in_stock = Variant::where('product_id', $product->id_product)
                             ->sum('quantity');

    // ✅ Tổng số sản phẩm đã bán
    $total_sold = DB::table('order_items')
        ->join('orders', 'order_items.order_id', '=', 'orders.id_order')
        ->join('variant', 'order_items.variant_id', '=', 'variant.id_variant')
        ->where('variant.product_id', $product->id_product)
        ->whereIn('orders.status', ['shipping', 'completed']) // chỉ tính đơn đã giao
        ->sum('order_items.quantity');

    return view('admin.products.show', compact(
        'product', 
        'categories', 
        'album_products', 
        'total_in_stock', 
        'total_sold'
    ));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
      $categories= Category::all();
      $brands= Brand::all();
      $product->load('albumProducts', 'category','brand');
        return view('admin.products.edit', compact('product', 'categories','brands'));
    }
    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Product $product)
{
    $request->validate([
        'name_product' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:category,id_category',
        'brand_id'    => 'required|exists:brands,id_brand', 
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $data = $request->only(['name_product', 'price', 'category_id', 'description'  ]);

    // Nếu có ảnh mới
    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu tồn tại
        $oldImagePath = storage_path('app/public/' . $product->image);
        if (!empty($product->image) && file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }

        // Lưu ảnh mới
        $path = $request->file('image')->store('public/products');
        $data['image'] = str_replace('public/', '', $path);
    }

    // Cập nhật sản phẩm
    $product->update($data);

    return redirect()->route('admin.products.index')->with('success', 'Cập nhật thành công!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // 1. Xóa ảnh chính của sản phẩm (nếu có)
        if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
            unlink(public_path('uploads/' . $product->image));
        }

        // 2. Lấy các biến thể thuộc sản phẩm
        $variants = Variant::where('product_id', $product->id_product)->get();

        foreach ($variants as $variant) {
            // Xóa ảnh của biến thể (nếu có)
            if ($variant->image && file_exists(public_path('uploads/' . $variant->image))) {
                unlink(public_path('uploads/' . $variant->image));
            }
            $variant->delete(); // xóa mềm biến thể
        }

        // 3. Xóa mềm sản phẩm
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Đã xóa sản phẩm và các biến thể kèm ảnh.');
    }
    /**
     * Search for products by name.
     */
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

    /**
     * Hiển thị danh sách sản phẩm đã xóa mềm
     */
    public function trash(Request $request)
    {
        $categoris = Category::all();
        $productsQuery = Product::onlyTrashed()->with(['category', 'advice_product']);

        if ($request->filled('keyword')) {
            $productsQuery->where('name_product', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('category')) {
            $productsQuery->where('category_id', $request->category);
        }

        $products = $productsQuery->latest()->paginate(10);

        return view('admin.products.trash', compact('products', 'categoris'));
    }

    /**
     * Khôi phục sản phẩm đã xóa mềm
     */
    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->variants()->onlyTrashed()->restore(); // Khôi phục biến thể
        $product->albumProducts()->onlyTrashed()->restore(); // Khôi phục ảnh album
        $product->advice_Product()->onlyTrashed()->restore(); // Khôi phục khuyến mãi
        $product->restore(); // Khôi phục sản phẩm

        return redirect()->route('admin.products.trash')->with('success', 'Khôi phục sản phẩm thành công!');
    }

    /**
     * Xóa cứng sản phẩm và các bản ghi liên quan
     */
    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->variants()->onlyTrashed()->forceDelete(); // Xóa cứng biến thể
        $product->albumProducts()->onlyTrashed()->forceDelete(); // Xóa cứng ảnh album
        $product->advice_Product()->onlyTrashed()->forceDelete(); // Xóa cứng khuyến mãi
        $product->forceDelete(); // Xóa cứng sản phẩm

        return redirect()->route('admin.products.trash')->with('success', 'Xóa vĩnh viễn sản phẩm thành công!');
    }
    public function toggleVisibility($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $product->visibility = $request->visibility;
        $product->save();

        $message = $product->visibility === 'visible' 
            ? '✅ Sản phẩm đã được hiển thị.' 
            : '🚫 Sản phẩm đã bị ẩn.';

        return response()->json([
            'success' => true,
            'message' => $message,
            'visibility' => $product->visibility
        ]);
    }


    



}
