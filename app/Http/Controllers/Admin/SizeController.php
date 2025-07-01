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
        $sizes = Size::orderBy('id_size', 'asc')->paginate();
        return view('admin.size.index', compact('sizes'));
    }

       public function create()
    {
        return view('admin.size.create');
    }

public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Size::create($request->all());

        return redirect()->route('admin.sizes.index')->with('success', 'Thêm danh mục mới thành công.');
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
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $size->update($request->all());

        return redirect()->route('admin.sizes.index')->with('success', 'Cập nhật danh mục mới thành công.');
    }

    public function destroy(Size $size)
    {
        $size->delete();

        return redirect()->route('admin.sizes.index')->with('success', 'Xóa danh mục thành công.');
    }
}
