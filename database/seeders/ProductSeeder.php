<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'title' => 'New Product',
                'price' => '6466000',
                'description' => 'Description',
                'image' => null,
                'image_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Another Product',
                'price' => '6466',
                'description' => 'Another Description',
                'image' => null,
                'image_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'title' => 'Another New Product',
                'price' => '6000',
                'description' => 'Another New Description',
                'image' => null,
                'image_url' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($products as $product)
            DB::table('products')->insert($product);

    }
}
