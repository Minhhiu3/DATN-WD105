<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlbumProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class AlbumProductController extends Controller
{
    public function index()
    {
       $album_products = AlbumProduct::with('product')->get();
        return view('admin.Album_product.index', compact('albums'));
    }

    public function create()
    {   
        $product_id = $_GET['id']; // Lấy giá trị từ query string
        $product = Product::find($product_id);
        return view('admin.Album_product.create', compact('product'));
    }

public function store(Request $request)
{
    //  Validate dữ liệu
    $validated = $request->validate([
        'product_id' => 'required|integer|exists:products,id_product',
        'album' => 'required',
        'album.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048', // validate từng file trong mảng
    ], [
        // Báo lỗi album ảnh sản phẩm
        'album.*.required' => 'Bạn cần tải lên hình ảnh album.',
        'album.*.image' => 'Mỗi ảnh trong album phải là hình ảnh (jpeg, jpg, png).',
        'album.*.mimes' => 'Ảnh trong album chỉ chấp nhận jpeg, jpg, png.',
        'album.*.max'   => 'Ảnh trong album không được vượt quá 2MB.',
    ]);
    
    //  Xử lý upload nhiều ảnh
    if ($request->hasFile('album')) {
        foreach ($request->file('album') as $file) {
            $filename = time().'_'.$file->getClientOriginalName(); // tránh trùng tên file
            $path = $file->storeAs('images', $filename, 'public'); // lưu file vào storage/app/public/images

            // Lưu từng ảnh vào DB
            AlbumProduct::create([
                'product_id' => $validated['product_id'],
                'image' => $path,
            ]);
        }
    } else {
        return back()->with('error', 'Không tìm thấy file ảnh.');
    }

    //  Điều hướng kèm thông báo
    return redirect()
        ->route('admin.album-products.show', $request->product_id)
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
      return redirect()->route('admin.album-products.show', ['album_product' => $product_id])
    ->with('success', 'Xóa thành công!');
    }


}
