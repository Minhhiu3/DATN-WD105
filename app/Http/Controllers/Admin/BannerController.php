<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Models\Banner;

use Illuminate\Support\Facades\App;

use Illuminate\Http\Request;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();
        return view('admin.banner.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Banner $banner)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $banner = new Banner();
        $banner->name = $request->name;

        if ($request->hasFile('image')) {
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->save();
        return redirect()->route('banner.index')->with('success', 'Banner created successfully.');
    }
    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', compact('banner'));
    }
    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $banner->name = $request->name;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $banner->image = $request->file('image')->store('banners', 'public');
        }

        $banner->save();

        return redirect()->route('banners.index')->with('success', 'Banner cập nhật thành côngcông.');
    }
    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('banners.index')->with('success', 'Banner xóa thành công.');
    }
}
