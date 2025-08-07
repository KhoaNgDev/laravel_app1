<?php

namespace Database\Seeders;

use App\Models\MstCustomer;
use App\Models\MstProduct;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
            // MstCustomer::factory(30)->create();
        User::factory(30)->create();
        // MstProduct::factory(30)->create();


    }
}
