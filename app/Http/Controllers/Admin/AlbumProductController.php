<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AlbumProduct;
use Illuminate\Support\Facades\Storage;
class AlbumProductController extends Controller
{
    //
    public function index()
    {
        // Logic to display a list of album products

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
        'image' => 'required|image|mimes:jpeg,png|max:2048' // Kiểm tra file là ảnh thực
    ]);

    // Xử lý ảnh (có kiểm tra tồn tại file)
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('public/images');
        $data['image'] = str_replace('public/', '', $path); // Lưu path ngắn
    } else {
        return back()->with('error', 'Không tìm thấy file ảnh');
    }

    // Lưu database
    AlbumProduct::create($data);

    // Sửa tên route cho đúng (Album thay vì Ablum)
    return redirect()->route('Ablum_products.show', $request->product_id)
        ->with('success', 'Upload ảnh thành công!');
}
    
    
    public function show($id)
    {
        $album_products = AlbumProduct::where('product_id', $id)->get();
    
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
    public function destroy(AlbumProduct $album_product)
{
    // $imagePath = 'storage/public/images/' . $album_product->image;
    // if (file_exists($imagePath)) {
    //     unlink($imagePath);
    // }
    $product_id = $album_product->product_id; // Lưu lại ID trước khi xóa
    $album_product->delete(); // Biến kiểm tra
$a=1;

    return redirect()->route('Ablum_products.show',
        $a
    )->with('success', 'Xóa thành công!');
}

    
    
    

}
