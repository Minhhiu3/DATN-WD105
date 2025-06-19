<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlbumProduct;
use Illuminate\Support\Facades\Storage;

class AlbumProductController extends Controller
{
    public function index()
    {
        $albums = AlbumProduct::with('product')->get();
        return view('album_products.index', compact('albums'));
    }

    public function create()
    {
        return view('admin.Album_product.create');
    }

    public function store(Request $request) 
    {
        // Validate
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id_product',
            'image' => 'required|image|mimes:jpeg,png|max:2048'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images');
            $data['image'] = str_replace('public/', '', $path);
        } else {
            return back()->with('error', 'Không tìm thấy file ảnh');
        }

        // Save to database
        AlbumProduct::create($data);

        // Fix typo in route name (Ablum -> Album)
        return redirect()->route('Ablum_products.show', $request->product_id)
            ->with('success', 'Upload ảnh thành công!');
    }

    public function show($id)
    {
        $album_products = AlbumProduct::where('product_id', $id)->get();
        return view('admin.Album_product.index', compact('album_products'));
    }
    public function showAblum($product_id)
    {
        $album_products = AlbumProduct::where('product_id', $product_id)->get();
        return view('admin.Album_product.index', compact('album_products'));
    }
    public function edit($id)
    {
        // Logic to show the form for editing a specific album product
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific album product
    }

    public function destroy($id_album_product)
    {
        $album_product = AlbumProduct::findOrFail($id_album_product);
        $product_id = $album_product->product_id;
        $imagePath = storage_path('app/public/' . $album_product->image);

        // Nếu ảnh tồn tại và là file thì xóa
        if (!empty($album_product->image) && file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }
        // Xóa bản ghi album
        $album_product->delete();

        // Điều hướng về trang hiển thị album theo product_id
        return redirect()->route('AblumProducts.show_ablum', ['product_id' => $product_id])
        ->with('success', 'Xóa thành công!');
    }


}