<?php

use App\Models\Transaction;
use App\User;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    $user = factory(User::class)->create();
    return [
        'user_id'           => $user->id,
        'transaction_type'  => Transaction::DEBIT,
        'amount'            => $faker->randomFloat()
    ];
});
