<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller

{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('client.pages.home', compact('banners'));
    }
}
