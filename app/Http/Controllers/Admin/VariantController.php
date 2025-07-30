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
            ->get()
            // Chỉ lấy những variant có quan hệ color tồn tại
            ->filter(function ($variant) {
                return $variant->color !== null;
            });

        $groupedVariants = $variants->groupBy('color_id');

        return view('admin.variants.index', compact('groupedVariants', 'id_product'));
    }



    public function create()
    {
        $products = Product::all();
        $sizes = Size::all();
        return view('admin.variants.create', compact('products', 'sizes'));
    }
    public function create_item(Request $request)
    {
        $productId = $request->query('product_id');
        $colorId = $request->query('color_id');

        if (!$productId || !$colorId) {
            return redirect()->back()->with('error', 'Thiếu product_id hoặc color_id trong URL.');
        }

        $product = Product::findOrFail($productId);
        $color = Color::findOrFail($colorId);

        // 1. Lấy ID size đã tồn tại trong bảng variants
        $usedSizeIds = Variant::where('product_id', $productId)
                            ->where('color_id', $colorId)
                            ->pluck('size_id')
                            ->toArray();

        // 2. Lọc những size CHƯA bị dùng
        $sizes = Size::whereNotIn('id_size', $usedSizeIds)->get();

        return view('admin.variants.create_item', compact('product', 'color', 'sizes'));
    }
    public function storeItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id_product',
            'variants' => 'required|array',
            'variants.*.color_id' => 'required|exists:colors,id_color',
            'variants.*.children' => 'required|array',
            'variants.*.children.*.size_id' => 'required|exists:size,id_size',
            'variants.*.children.*.price' => 'required|numeric|min:0',
            'variants.*.children.*.quantity' => 'required|integer|min:0',
        ]);

        foreach ($request->variants as $variant) {
            $colorId = $variant['color_id'];
            foreach ($variant['children'] as $child) {
                Variant::create([
                    'product_id' => $request->product_id,
                    'color_id' => $colorId,
                    'size_id' => $child['size_id'],
                    'price' => $child['price'],
                    'quantity' => $child['quantity'],
                ]);
            }
        }

        return redirect()->route('admin.variants.show', $request->product_id)
                         ->with('success', 'Thêm biến thể thành công!');
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
            $imagePath = $file->store('colors', 'public');
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
                !Size::where('id_size', $child['size_id'])->exists() || // ❗ Sửa ở đây
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

    return redirect()->route('admin.variants.show', $id_product)->with('success', 'xóa biến thể thành công!');
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
