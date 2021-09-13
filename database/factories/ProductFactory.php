<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name();
        $slug = Str::slug($name).'-'.uniqid();
        return [
            'name' => $name,
            'slug' => $slug,
            'brand_id' => $this->faker->randomDigit,
            'category_id' => $this->faker->randomDigit,
            'price' => $this->faker->numberBetween($min = 1000, $max = 9000) // password
        ];
    }

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Product::factory()
            ->count(50)
            ->create();
    }
}
