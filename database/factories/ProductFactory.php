<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\User;
use App\Product;
use App\Seller;
use App\Transaction;

$factory->define(Product::class, function (Faker $faker) {
    $word = $faker->word;
    return [
        'name' => $word,
        'slug' => str_slug($word),
        'description' => $faker->paragraph(2),
        'quantity'=> $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
        'image' => $faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
        'seller_id' => User::all()->random()->id,
    ];
});

// $factory->define(Transaction::class, function (Faker $faker) {
//     $seller = Seller::has('products')->get()->random();
//     $buyer = User::all()->except($seller->id)->random();
//     return [
//         'quantity' => $faker->numberBetween(1, 3),
//         'buyer_id' => $buyer->id,
//         'product_id' => $seller->products->random()->id,
//     ];
// });
