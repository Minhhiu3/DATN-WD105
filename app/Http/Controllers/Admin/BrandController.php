<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class BrandController extends Controller
{
        public function index()
    {
        $brands = Brand::latest()->paginate(6);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        try {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            // lỗi tên thương hiệu
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.string'   => 'Tên thương hiệu không hợp lệ.',
            'name.max'      => 'Tên thương hiệu không được vượt quá 255 ký tự.',
            'name.unique'   => 'Tên thương hiệu này đã tồn tại.',
            // lỗi logo hình ảnh
            'logo.required'   => 'Vui lòng tải ảnh lên.',
            'logo.image'   => 'Tệp tải lên phải là hình ảnh.',
            'logo.mimes'   => 'Logo chỉ chấp nhận định dạng: jpeg, png, jpg.',
            'logo.max'     => 'Kích thước logo không được vượt quá 2MB.',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        Brand::create([
            'name' => $request->name,
            'logo' => $logoPath,
        ]);
// dd($logoPath);
        return redirect()->route('admin.brands.index')->with('success', 'Thêm thương hiệu mới thành công.');
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

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255' . $brand->id_brand . ',id_brand',
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
        ], [
            // lỗi tên thương hiệu
            'name.required' => 'Vui lòng nhập tên thương hiệu.',
            'name.string'   => 'Tên thương hiệu không hợp lệ.',
            'name.max'      => 'Tên thương hiệu không được vượt quá 255 ký tự.',
            // lỗi logo hình ảnh
            'logo.mimes'   => 'Logo chỉ chấp nhận định dạng: jpeg, png, jpg.',
            'logo.max'     => 'Kích thước logo không được vượt quá 2MB.',
        ]);

        // Trường hợp tên size đã tồn tại trong DB (trùng với size khác)
        $exists =$brand->where('name', $request->name)
                    ->where('id_brand', '!=', $brand->id_brand) // loại trừ size hiện tại
                    ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Tên thương hiệu này đã tồn tại trong hệ thống.'])
                ->withInput();
        }
        $logoPath = $brand->logo;
        if ($request->hasFile('logo')) {
            if ($logoPath && file_exists(public_path('storage/' . $logoPath))) {
                unlink(public_path('storage/' . $logoPath));
            }
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        $brand->update([
            'name' => $request->name,
            'logo' => $logoPath,
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Cập nhật thương hiệu thành công.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->logo && file_exists(public_path('storage/' . $brand->logo))) {
            unlink(public_path('storage/' . $brand->logo));
        }
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Xóa thương hiệu thành công.');
    }
        /**
     * Hiển thị danh sách brand trong thùng rác
     */
    public function trash(Request $request)
    {
        $brandsQuery = Brand::onlyTrashed();

        if ($request->filled('keyword')) {
            $brandsQuery->where('name', 'like', '%' . $request->keyword . '%');
        }

        $brands = $brandsQuery->latest('id_brand')->paginate(5);

        return view('admin.brands.trash', compact('brands'));
    }

    /**
     * Khôi phục brand đã xóa mềm
     */
    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->restore();

        return redirect()->route('admin.brands.trash')
            ->with('success', 'Khôi phục thương hiệu thành công!');
    }

    /**
     * Xóa vĩnh viễn brand
     */
    public function forceDelete($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->forceDelete();

        return redirect()->route('admin.brands.trash')
            ->with('success', 'Xóa vĩnh viễn thương hiệu thành công!');
    }

}
