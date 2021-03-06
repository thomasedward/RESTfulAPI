<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
use App\User;
use App\Category;
use App\Product;
use App\Seller;
use App\Transaction;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

//USer
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $verified =  $faker->randomElement([User::VERIFIED_USER,User::UNVERIFIED_USER]),
        'verification_token' =>  $verified == User::VERIFIED_USER ? null : User::generateVerificationCode(),
        'admin' => $admin =  $faker->randomElement([User::VERIFIED_USER,User::UNVERIFIED_USER]),
    ];
});
//Category
$factory->define(Category::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1) ,

    ];
});
//Product
$factory->define(Product::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1) ,
        'quantity' =>$faker->numberBetween(1,10),
        'status' => $faker->randomElement([Product::UNAVAILABLE_PRODUCT,Product::AVAILABLE_PRODUCT]) ,
        'image' => $faker->randomElement(['1.jpg','2.jpg','3.jpg']),
        'seller_id' => User::all()->random()->id,
       // 'seller_id' => User::inRandomOrder()->first()->id,

    ];
});
//Transactions
$factory->define(Transaction::class, function (Faker\Generator $faker) {

    $seller = Seller::has('products')->get()->random();
    $buyer = User::all()->except($seller->id)->random();

    return [
        'quantity' =>$faker->numberBetween(1,3),
        'buyer_id' => $buyer->id,
        'product_id' => $seller->products->random()->id,

    ];
});