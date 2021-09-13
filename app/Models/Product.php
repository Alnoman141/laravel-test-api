<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'brand_id',
        'category_id',
        'price',
    ];

    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function productImages(){
        return $this->hasMany(ProductImage::class);
    }
}
