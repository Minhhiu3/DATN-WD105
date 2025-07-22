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
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id_product',
        'variants' => 'required|array|min:1',
        'variants.*.size_id' => 'required|exists:size,id_size',
        'variants.*.price' => 'required|numeric|min:0',
        'variants.*.quantity' => 'required|integer|min:0',
    ]);

    foreach ($validated['variants'] as $variant) {
        // Kiểm tra biến thể đã tồn tại chưa
        $existingVariant = Variant::where('product_id', $validated['product_id'])
            ->where('size_id', $variant['size_id'])
            ->first();

        if ($existingVariant) {
            // ✅ Nếu đã tồn tại: cập nhật giá và cộng dồn số lượng
            $existingVariant->price = $variant['price']; // Cập nhật giá mới
            $existingVariant->quantity += $variant['quantity']; // Cộng dồn số lượng
            $existingVariant->save();
        } else {
            // ✅ Nếu chưa tồn tại: tạo mới
            Variant::create([
                'product_id' => $validated['product_id'],
                'size_id' => $variant['size_id'],
                'price' => $variant['price'],
                'quantity' => $variant['quantity'],
            ]);
        }
    }

    return redirect()->route('admin.variants.show', $validated['product_id'])
        ->with('success', 'Đã thêm hoặc cập nhật biến thể thành công!');
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
    // ajax sửa số lượng
    public function updateQuantity(Request $request, $id)
    {
        $variant = Variant::findOrFail($id);
        $variant->quantity = $request->quantity;
        $variant->save();

        return response()->json(['success' => true]);
    }

}
