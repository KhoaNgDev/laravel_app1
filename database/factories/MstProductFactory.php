<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MstProductFactory extends Factory
{

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            'product_id' => strtoupper(Str::substr($name, 0, 1)) . str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT),
            'product_name' => $name,
            'product_image' => null,
            'product_price' => $this->faker->numberBetween(1000000, 99999999),
            'is_sales' => $this->faker->randomElement(['in_storage', 'stop_sales', 'out_of_stock']),
            'product_description' => $this->faker->paragraph(),
        ];
    }

}