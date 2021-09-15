<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
use File;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['index']]);
    }

    // index method is used for load all products and handle search
    public function index(Request $request){
        $searchParams = $request->all();
        $productQuery = Product::query();
        $keyword = Arr::get($searchParams, 'keyword', '');

        if (!empty($keyword)) {
            $productQuery->where('name', 'LIKE', '%' . $keyword . '%');
        }

        return ProductResource::collection($productQuery->orderBy('id', 'desc')->get());
        
    }

    // uploadImage is used for save image on public path
    public function uploadImage(Request $request){
        if($request->file('photo')){
            $file_name = 'product-'.uniqid().'.'.$request->file('photo')->extension();
            $path = $request->file('photo')->storePubliclyAs(
                'images',
                $file_name
            );
            return response()->json(['imageName' => $path], 200);
        } else {
            return response()->json(['No image found']);
        }
    }

    // store is used for store new product
    public function store(Request $request){
        $product = new Product();
        $product->slug = Str::slug($request->name).'-'.uniqid();
        $this->saveData($request, $product);
        try {
            $product->save();
            if(count($request->images) > 0){
                $this->saveImages($request->images, $product);
            }
            return response()->json(['success' => 'product added successfully']);

        } catch (\Exception $ex) {
            return response()->json(['error' => $ex->getMessage()], 403);
        }
    }

    // saveData is used for store and update method data validationa and save them on database
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
            $product->brand_id = $request->brand_id;
            $product->category_id = $request->category_id;
            $product->price = $request->price;
        }
    }

    // saveImage is used for save products image in database
    public function saveImages($images, $product){
        foreach($images as $image){
            $img = new ProductImage();
            $img->image = $image['image'];
            $img->product_id = $product->id;
            $img->save();
        }
    }

    // show methos is used for show a single product information
    public function show($slug){
        $product = Product::where('slug', $slug)->first();
        if(isset($product)){
            return new ProductResource($product);
        } else {
            return response()->json(['error' => 'product not found']);
        }
    }

    // update is used for update product information
    public function update(Request $request, $slug){
        $product = Product::where('slug', $slug)->first();
        if(isset($product)){
            $this->saveData($request, $product);
            try {
                $product->save();
                if(count($request->images) > 0){
                    $this->saveImages($request->images, $product);
                }
                return response()->json(['success' => 'product updated successfully']);

            } catch (\Exception $ex) {
                return response()->json(['error' => $ex->getMessage()], 403);
            }
        } else {
            return response()->json(['error' => 'product not found']);
        }
    }

    // delete is used for delete a single product and its image
    public function delete($slug){
        $product = Product::where('slug', $slug)->first();
        if(isset($product)){
            if(count($product->images) > 0){
                foreach ($product->images as $image){
                    if (File::exists($image->image)){
                        File::delete($image->image);
                    }
                }
            }
            $product->delete();
            return response()->json(['success' => 'product has been deleted']);
        } else {
            return response()->json(['error' => 'product not found']);
        }
    }

    // delete image is used for delete a single image
    public function deleteImage(Request $request){
        if($request->id === null){
            if (File::exists($request->image)){
                File::delete($request->image);
            }
            return response()->json(['success' => 'image has been deleted']);
        } else {
            $img = ProductImage::find($request->id);
            if($img){
                if (File::exists($img->image)){
                    File::delete($img->image);
                }
                $img->delete();
                return response()->json(['success' => 'image has been deleted']);
            }
        }
    }

    // getValidationRules is used for validate requiest data
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
