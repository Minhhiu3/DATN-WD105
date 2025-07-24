<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Variant;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;

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
    $variants = Variant::with(['product', 'size', 'color'])
        ->where('product_id', $id_product)
        ->get();

    $groupedVariants = $variants->groupBy('color_id');

    return view('admin.variants.index', compact('groupedVariants','id_product'));
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
    ]);

    foreach ($request->input('variants', []) as $index => $variant) {
        if (empty($variant['color_name']) || empty($variant['children']) || !is_array($variant['children'])) {
            continue;
        }

        // ✅ Lấy đúng file ảnh theo chỉ số
        $file = $request->file("variants.$index.image");
        $imagePath = null;

        if ($file && $file instanceof \Illuminate\Http\UploadedFile) {
            $imagePath = $file->store('colors', 'public'); // Lưu vào storage/app/public/colors
        }

        // ✅ Tạo màu mới
        $color = Color::create([
            'name_color' => $variant['color_name'],
            'image' => $imagePath,
        ]);

        // ✅ Lặp tạo biến thể theo size
        foreach ($variant['children'] as $child) {
            if (
                empty($child['size_id']) ||
                !\App\Models\Size::where('id_size', $child['size_id'])->exists() ||
                !isset($child['price']) || !is_numeric($child['price']) ||
                !isset($child['quantity']) || !is_numeric($child['quantity'])
            ) {
                continue;
            }

            Variant::create([
                'product_id' => $validated['product_id'],
                'color_id' => $color->id_color,
                'size_id' => $child['size_id'],
                'price' => $child['price'],
                'quantity' => $child['quantity'],
            ]);
        }
    }

    // ✅ Return lại như ban đầu
    return redirect()->route('admin.variants.show', $validated['product_id'])
        ->with('success', 'Đã thêm biến thể thành công!');
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
