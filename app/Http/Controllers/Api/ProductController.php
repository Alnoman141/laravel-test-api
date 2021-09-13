<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    public function index(){
        $products = Product::all();
        if(isset($products)){
            return response()->json(['products' => $products], 200);
        } else {
            return response()->json(['error' => 'No products found']);
        }
        
    }

    public function store(Request $request){
        $product = new Product();
        $this->saveData($request, $product);
        try {
            $product->save();
            return response()->json(['success' => 'product added successfully']);

        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    public function saveData($request, $product){
        // check validation rules from getValidationRules method
        $validator = Validator::make(
            $request->all(),
            array_merge(
                $this->getValidationRules(),
            )
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 403);
        }else{
            // creating new product
            $product->name = $request->name;
            $product->slug = Str::slug($request->name).'-'.uniqid();
            $product->brand_id = $request->brand_id;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
        }
    }

    public function show($slug){
        $product = Product::where('slug', $slug)->first();
        if(isset($product)){
            return response()->json(['product' => $product], 200);
        } else {
            return response()->json(['error' => 'product not found']);
        }
    }

    public function update(Request $request, $slug){
        $product = Product::where('slug', $slug)->first();
        if(isset($product)){
            $this->saveData($request, $product);
            try {
                $product->save();
                return response()->json(['success' => 'product updated successfully']);

            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 403);
            }
        } else {
            return response()->json(['error' => 'product not found']);
        }
    }

    public function delete($slug){
        $product = Product::where('slug', $slug)->first();
        if(isset($product)){
            $product->delete();
            return response()->json(['success' => 'product has been deleted']);
        } else {
            return response()->json(['error' => 'product not found']);
        }
    }

    private function getValidationRules($project = null,$isNew = true)
    {
        return [
            'name' => 'required|string',
            'category_id' => 'required|numeric',
            'brand_id' => 'required|numeric',
            'price' => 'required|numeric',
        ];
    }
}
