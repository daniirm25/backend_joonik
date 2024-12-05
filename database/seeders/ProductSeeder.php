<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new Product();
        $product->name = "Arroz";
        $product->code = 321;
        $product->price = 20000;
        $product->description = "Arroz de 5KG";
        $product->save();

        
        $product = new Product();
        $product->name = "Maiz";
        $product->code = 344;
        $product->price = 15000;
        $product->description = "Maiz de 10KG";
        $product->save();
    }
}
