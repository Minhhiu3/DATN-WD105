<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::latest()->paginate();
        return view('admin.colors.index', compact('colors'));
    }

       public function create()
    {
        return view('admin.colors.create');
    }

public function store(Request $request)
    {
        $request->validate([
            'name_color' => 'required|string|max:255|unique:Colors,name_color',
        ]);

        Color::create($request->all());

        return redirect()->route('admin.colors.index')->with('success', 'Thêm màu mới thành công.');
    }

    public function show(Color $color)
    {
        return view('admin.colors.show', compact('color'));
    }

public function edit(Color $color)
{
    $productId = request('product_id');
    return view('admin.colors.edit', compact('color', 'productId'));
}

public function update(Request $request, Color $color)
{
    $request->validate([
        'name_color' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Cập nhật tên màu (chính xác theo tên cột DB)
    $color->name_color = $request->input('name_color');

    // Nếu có ảnh mới được tải lên
    if ($request->hasFile('image')) {
        // Xóa ảnh cũ nếu có
        if ($color->image && Storage::disk('public')->exists($color->image)) {
            Storage::disk('public')->delete($color->image);
        }

        // Lưu ảnh mới vào thư mục storage/app/public/colors
        $imagePath = $request->file('image')->store('colors', 'public');
        $color->image = $imagePath;
    }

    // Lưu lại vào database
    $color->save();
    return redirect()->route('admin.variants.show', $request->product_id)->with('success', 'Cập nhật màu thành công.');
}


    public function destroy(Color $Color)
    {
        $Color->delete();

        return redirect()->route('admin.colors.index')->with('success', 'Xóa màu thành công.');
    }
}
