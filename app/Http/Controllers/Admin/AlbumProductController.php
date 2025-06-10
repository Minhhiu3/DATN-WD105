<?php

namespace App\Http\Controllers;

use App\Models\AlbumProduct;
use Illuminate\Http\Request;

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
        // Logic to show the form for creating a new album product
    }
    public function store(Request $request)
    {
        // Logic to store a new album product
    }
    public function show($id)
    {
        // Logic to display a specific album product
    }
    public function edit($id)
    {
        // Logic to show the form for editing a specific album product
    }
    public function update(Request $request, $id)
    {
        // Logic to update a specific album product
    }
    public function destroy($id)
    {
        // Logic to delete a specific album product
    }

}
