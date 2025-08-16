<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::latest()->paginate();
        return view('admin.size.index', compact('sizes'));
    }

       public function create()
    {
        return view('admin.size.create');
    }

// public function store(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255|unique:size,name',
//         ]);

//         Size::create($request->all());

//         return redirect()->route('admin.sizes.index')->with('success', 'Thêm danh mục mới thành công.');
//     }
public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|integer|max:255|unique:size,name',
        ], [
            'name.required' => 'Vui lòng nhập tên size.',
            'name.integer'   => 'Tên size không hợp lệ.',
            'name.max'      => 'Tên size không được vượt quá 255 ký tự.',
            'name.unique'   => 'Tên size này đã tồn tại.',
        ]);

        Size::create($request->all());

        return redirect()->route('admin.sizes.index')
                         ->with('success', 'Thêm size mới thành công.');
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

    public function show(Size $size)
    {
        return view('admin.size.show', compact('size'));
    }

    public function edit(Size $size)
    {
        return view('admin.size.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        try {
        $request->validate([
            'name' => 'required|integer|max:255',
        ], [
            'name.required' => 'Vui lòng nhập tên size.',
            'name.integer'   => 'Tên size không hợp lệ.',
            'name.max'      => 'Tên size không được vượt quá 255 ký tự.',
        ]);
        // Trường hợp tên size đã tồn tại trong DB (trùng với size khác)
        $exists = Size::where('name', $request->name)
                    ->where('id_size', '!=', $size->id_size) // loại trừ size hiện tại
                    ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Tên size này đã tồn tại trong hệ thống.'])
                ->withInput();
        }
        $size->update($request->all());

        return redirect()->route('admin.sizes.index')->with('success', 'Cập nhật Kích thước sản phẩm thành công.');
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

    public function destroy(Size $size)
    {
        // Kiểm tra xem size có đang được dùng ở bảng variant không
        $count = $size->variants()->count(); // Giả sử đã có quan hệ variants trong model Size

        if ($count > 0) {
            return redirect()->route('admin.sizes.index')
                ->with('error', 'Không thể xóa size này vì đang có sản phẩm sử dụng!');
        }

        $size->delete();

        return redirect()->route('admin.sizes.index')->with('success', 'Xóa danh mục thành công.');
    }
}
