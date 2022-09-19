<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    public function makeCategory(){
        $categories = [
            "Mobile Phone",
            "Laptop",
            "Social Media",
            "Search Engeen",
            "Car",
            "Programming Language",
            "Sports",
            "Smart Watch",
            "HeadPhone",
            "Tech",
        ];

        foreach ($categories as $category) {
            $newCat = Category::create([
                'name' => $category,
            ]);

        }
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            $this->makeCategory()
        ];
    }
}
