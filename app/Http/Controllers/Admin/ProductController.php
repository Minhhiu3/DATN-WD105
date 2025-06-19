<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

       $products = Product::with(['category', 'albumProducts'])->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories= Category::all();
        // Trả về view để tạo sản phẩm mới
          return view('admin.products.create', compact('categories'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_product' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:category,id_category',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png|max:2048'
        ]);

        // Lưu dữ liệu form
        $data = $request->only(['name_product', 'price', 'category_id', 'description']);

        // Xử lý lưu ảnh
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/products');
            $data['image'] = str_replace('public/', '', $path); // lưu path để dùng trong asset()
        } else {
            return back()->with('error', 'Không tìm thấy file ảnh');
        }

        // Tạo sản phẩm
        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Thêm mới thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
      $categories= Category::all();
        return view('admin.products.show', compact('product','categories'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
      $categories= Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
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
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png|max:2048'
    ]);

    $data = $request->only(['name_product', 'price', 'category_id', 'description','image',  ]);

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

    return redirect()->route('products.index')->with('success', 'Cập nhật thành công!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {

        $product->delete();


        return redirect()->route('products.index')->with('success', 'Xóa Sản phẩm thành công.');
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




}
