<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    //
      public function getProvinces()
    {
        $response = Http::get('https://vietnamlabs.com/api/vietnamprovince');
        return $response->json();
    }

    public function getDistricts($province_id)
    {
        $response = Http::get("https://vietnamlabs.com/api/vietnamprovince/districts/$province_id");
        return $response->json();
    }

    public function getWards($district_id)
    {
        $response = Http::get("https://vietnamlabs.com/api/vietnamprovince/wards/$district_id");
        return $response->json();
    }
}
