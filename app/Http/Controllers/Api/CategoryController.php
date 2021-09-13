<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    public function index(){
        $categories = Category::all();
        if(isset($categories)){
            return response()->json(['categories' => $categories], 200);
        } else {
            return response()->json(['error' => 'No category not found']);
        }
    }
}
