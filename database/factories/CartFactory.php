<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cart;
use Faker\Generator as Faker;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        "user_id"=>$faker->uuid,
        "product_id" => $faker->randomDigit(1000, 3000),
        "thumbnail"=> $faker->imageUrl(),
        "name" =>   $faker->uuid,
        "sku" => $faker->ean13,
        "unit_price" => $faker->randomFloat(2, 100, 500),
        "discount" => 0.10
    ];
});
