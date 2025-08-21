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
        try {
        $request->validate([
            'name' => 'required|string|max:255|unique:banners,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required|exists:products,id_product',
        ], [
            // lỗi tên banner
            'name.required' => 'Vui lòng nhập tên banner.',
            'name.string'   => 'Tên banner không hợp lệ.',
            'name.max'      => 'Tên banner không được vượt quá 255 ký tự.',
            'name.unique'   => 'Tên banner này đã tồn tại.',
            // lỗi hình ảnh
            'image.required'   => 'Vui lòng tải ảnh lên.',
            'image.image'   => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'   => 'Logo chỉ chấp nhận định dạng: jpeg, png, jpg.',
            'image.max'     => 'Kích thước ảnh không được vượt quá 2MB.',
            // lỗi liên kết sản phẩm
            'product_id.required' => 'Vui lòng chọn sản phẩm liên kết.',
            'product_id.exists'   => 'Sản phẩm được chọn không tồn tại trong hệ thống.',
        ]);
        $banner = new Banner();
        $banner->name = $request->name;
        $banner->product_id = $request->product_id;

        if ($request->hasFile('image')) {
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->save();
        return redirect()->route('admin.banner.index')->with('success', 'Tạo mới banner thành công.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                            ->withErrors($e->errors()) // gửi lỗi về view
                            ->withInput()
                            ->with('error', 'Vui lòng kiểm tra các lỗi bên dưới.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Đã xảy ra lỗi hệ thống, vui lòng thử lại.');
        }
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required|exists:products,id_product',
        ], [
            // lỗi tên banner
            'name.required' => 'Vui lòng nhập tên banner.',
            'name.string'   => 'Tên banner không hợp lệ.',
            'name.max'      => 'Tên banner không được vượt quá 255 ký tự.',
            // lỗi hình ảnh
            'image.required'   => 'Vui lòng tải ảnh lên.',
            'image.image'   => 'Tệp tải lên phải là hình ảnh.',
            'image.mimes'   => 'Logo chỉ chấp nhận định dạng: jpeg, png, jpg.',
            'image.max'     => 'Kích thước ảnh không được vượt quá 2MB.',
            // lỗi liên kết sản phẩm
            'product_id.required' => 'Vui lòng chọn sản phẩm liên kết.',
            'product_id.exists'   => 'Sản phẩm được chọn không tồn tại trong hệ thống.',
        ]);

        $banner->name = $request->name;
        $banner->product_id = $request->product_id;

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $request->file('image')->store('banners', 'public');
        }
        // Trường hợp tên banner đã tồn tại trong DB (trùng với banner khác)
        $exists =$banner->where('name', $request->name)
                    ->where('id', '!=', $banner->id) // loại trừ banner hiện tại
                    ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Tên banner này đã tồn tại trong hệ thống.'])
                ->withInput();
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
public function trash(Request $request)
{
    $bannersQuery = Banner::onlyTrashed()->with('product');

    // Tìm kiếm theo tên banner
    if ($request->filled('keyword')) {
        $bannersQuery->where('name', 'like', '%' . $request->keyword . '%');
    }

    $banners = $bannersQuery->latest('id')->paginate(5);

    return view('admin.banner.trash', compact('banners'));
}


    public function restore($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->restore();

        return redirect()->route('admin.banner.trash')
            ->with('success', 'Khôi phục banner thành công!');
    }

    public function forceDelete($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);

        // Xóa luôn ảnh khỏi storage nếu có
        if ($banner->image && \Storage::exists('public/' . $banner->image)) {
            \Storage::delete('public/' . $banner->image);
        }

        $banner->forceDelete();

        return redirect()->route('admin.banner.trash')
            ->with('success', 'Xóa vĩnh viễn banner thành công!');
    }

}
