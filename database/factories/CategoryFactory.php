<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Category;

$factory->define(Category::class, function (Faker $faker) {
    $word = $faker->word;
    return [
        'name' => $word,
        'slug' => str_slug($word),
        'description' => $faker->paragraph(1)
    ];
});
