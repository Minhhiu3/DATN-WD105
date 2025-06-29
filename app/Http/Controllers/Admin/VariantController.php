<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Variant;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    /**
     * Hiển thị danh sách biến thể.
     */
    public function index()
    {
        $variants = Variant::with(['product', 'size'])->get();
        return view('admin.variants.index', compact('variants'));
    }
    public function show($id_product)
    {
        $variants = Variant::with(['product', 'size'])
            ->where('product_id', $id_product)
            ->get();

        return view('admin.variants.index', compact('variants'));
    }
    /**
     * Hiển thị form tạo mới biến thể.
     */
    public function create()
    {
        $products = Product::all();
        $sizes = Size::all();
        return view('admin.variants.create', compact('products', 'sizes'));
    }

    /**
     * Lưu biến thể mới vào database.
     */
    public function store(Request $request)
{
    // Validate dữ liệu đầu vào
    $request->validate([
        'size_id' => 'required|exists:size,id_size',
        'product_id' => 'required|exists:products,id_product',
        'price' => 'required|numeric|min:0',
        'quantity' => 'required|integer|min:0',
    ]);

    // Kiểm tra nếu biến thể đã tồn tại (tránh bị trùng UNIQUE)
    $id_product=$request->product_id;
    $exists = Variant::where('product_id', $request->product_id)
        ->where('size_id', $request->size_id)
        ->exists();

    if ($exists) {
        return back()
            ->withInput()
            ->with('error', 'Biến thể này đã tồn tại (sản phẩm và size).');
    }

    // Tạo mới biến thể nếu chưa tồn tại
    Variant::create($request->only(['size_id','product_id', 'price', 'quantity']));

    return redirect()->route('admin.variants.show', $id_product)->with('success', 'Thêm biến thể thành công!');
}



    /**
     * Hiển thị form chỉnh sửa biến thể.
     */
    public function edit(Variant $variant)
    {
        $products = Product::all();  // Lấy danh sách sản phẩm để chọn lại
        $sizes = Size::all();        // Lấy danh sách size để chọn lại

        return view('admin.variants.edit', compact('variant', 'products', 'sizes'));
    }


    /**
     * Cập nhật biến thể.
     */
    public function update(Request $request, Variant $variant)
    {
        $request->validate([
            'size_id' => 'required|exists:size,id_size',
            'product_id' => 'required|exists:products,id_product',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
        ]);
        $id_product=$variant->product_id;
        $variant->update($request->all());

        return redirect()->route('admin.variants.show', $id_product)->with('success', 'Thêm biến thể thành công!');
    }

    /**
     * Xóa mềm biến thể.
     */
    public function destroy($id_variant)
    {
        $variant = Variant::findOrFail($id_variant);
        $id_product = $variant->product_id;
        $variant->forceDelete();

    return redirect()->route('admin.variants.show', $id_product)->with('success', 'Thêm biến thể thành công!');
    }
}
