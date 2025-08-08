<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MstCustomerFactory extends Factory
{

    public function definition(): array
    {
     
         return [
            'customer_name' => $this->faker->unique()->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'tel_num' => $this->faker->numerify('090#######'), 
            'address' => $this->faker->address(),
            'is_active' => $this->faker->boolean(80), 
        ];
    }

}
