<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\App;


use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
          return view('admin.categories.index', compact('categories'));
    }

       public function create()
    {
        return view('admin.categories.create');
    }

public function store(Request $request)
    {
        $request->validate([
            'name_category' => 'required|string|max:255|unique:category,name_category',
        ], [
            'name_category.required' => 'Vui lòng nhập tên danh mục.',
            'name_category.integer'   => 'Tên danh mục không hợp lệ.',
            'name_category.max'      => 'Tên danh mục không được vượt quá 255 ký tự.',
            'name_category.unique'   => 'Tên danh mục này đã tồn tại.',
        ]);

        Category::create($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục mới thành công.');
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name_category' => 'required|string|max:255'
        ], [
            'name_category.required' => 'Vui lòng nhập tên danh mục.',
            'name_category.integer'   => 'Tên danh mục không hợp lệ.',
            'name_category.max'      => 'Tên danh mục không được vượt quá 255 ký tự.',
        ]);
        // Trường hợp tên danh mục đã tồn tại trong DB (trùng với danh mục khác)
        $exists = Category::where('name_category', $request->name_category)
                    ->where('id_category', '!=', $category->id_category) // loại trừ size hiện tại
                    ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name_category' => 'Tên danh mục này đã tồn tại trong hệ thống.'])
                ->withInput();
        }
        
        $category->update($request->all());

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục mới thành công.');
    }

    public function destroy(Category $category)
    {
          if ($category->products()->count() > 0) {
        return redirect()->route('admin.categories.index')
            ->with('error', 'Không thể xóa danh mục vì vẫn chứa sản phẩm');
    }
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công.');
    }
}
