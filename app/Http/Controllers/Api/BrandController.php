<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    // index is used for load all the brand
    public function index(){
        $brands = Brand::all();
        if(isset($brands)){
            return response()->json(['brands' => $brands], 200);
        } else {
            return response()->json(['error' => 'No brand not found']);
        }
    }
}
