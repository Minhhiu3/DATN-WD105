<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::with('product')->get();
        return view('admin.banner.index', compact('banners'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.banner.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'nullable|exists:products,id_product',
        ]);
        $banner = new Banner();
        $banner->name = $request->name;
        $banner->product_id = $request->product_id;

        if ($request->hasFile('image')) {
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->save();
        return redirect()->route('admin.banner.index')->with('success', 'Tạo mới banner thành công.');
    }

    public function edit(Banner $banner)
    {
        $products = Product::all();
        return view('admin.banner.edit', compact('banner', 'products'));
    }

    public function update(Request $request, Banner $banner)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'nullable|exists:products,id_product',
        ]);

        $banner->name = $request->name;
        $banner->product_id = $request->product_id;

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->save();
        return redirect()->route('admin.banner.index')->with('success', 'Chỉnh sửa banner thành công.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }
        $banner->delete();
        return redirect()->route('admin.banner.index')->with('success', 'Xóa mềm banner thành công.');
    }
}
