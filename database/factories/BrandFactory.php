<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    public function makeBrand(){
        $brands = [
            "Apple",
            "Nokia",
            "Google",
            "Facebook",
            "Tesla",
            "Samsung",
            "HP",
            "Lenevo",
            "Laravel",
            "Vuejs",
        ];

        foreach ($brands as $brand) {
            $user = Brand::create([
                'name' => $brand,
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
            $this->makeBrand()
        ];
    }
}
