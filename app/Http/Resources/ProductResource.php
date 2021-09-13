<?php

namespace App\Http\Resources;

use App\Models\ProductImage;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $coverImage = ProductImage::where('product_id', $this->id)->first();
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand_id' => $this->brand_id,
            'brand' => $this->brand,
            'category_id' => $this->category_id,
            'category' => $this->category,
            'price' => $this->price,
            'coverImage' => $coverImage,
            'productImages' => $this->productImages,
        ];
    }
}
