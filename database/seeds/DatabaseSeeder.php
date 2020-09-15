<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Product;
use App\Category;
use App\Transaction;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        

        factory(User::class, 10)->create();
        factory(Category::class, 10)->create();
        factory(Product::class, 10)->create()->each(function($product) {
            $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');
            $product->categories()->attach($categories);
            }
        );

        factory(Transaction::class, 10)->create();

    }
}
