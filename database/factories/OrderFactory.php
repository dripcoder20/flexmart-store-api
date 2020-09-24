<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Cart;
use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        //
        'tracking_number' =>$faker->lexify("????-????"),
        'amount'=>$faker->randomFloat(2, 100, 1200),
        'user_id'=> $faker->uuid,
        'user'=> [
            'user_id'=> $faker->uuid,
            'name'=> $faker->name
        ],
        'shipping_information'=> [
            'address' => $faker->streetAddress,
            'barangay' => $faker->secondaryAddress,
            'city'=>$faker->city,
            'province'=>$faker->state
        ],
        'cart'=> factory(Cart::class)->create(),
        'status'=> OrderStatus::PENDING
    ];
});
