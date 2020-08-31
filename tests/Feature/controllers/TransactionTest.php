<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_list_user_transactions() {
        $this->withoutExceptionHandling();

        $user = $this->createUser();

        factory(Transaction::class, 10)->create([
            'user_id' => $user->id
        ]);

        $this->get("/api/$user->id/transactions")
            ->assertJsonCount(10, 'data');
    }

    /**
     * @test
     */
    public function should_list_user_transactions_to_spicified_limit() {
        $this->withoutExceptionHandling();

        $user = $this->createUser();

        factory(Transaction::class, 10)->create([
            'user_id' => $user->id
        ]);

        $this->get("/api/$user->id/transactions?limit=5")
            ->assertJsonCount(5, 'data');
    }

    /**
     * @test
     */
    public function should_get_specific_user_transactions() {
        $this->withoutExceptionHandling();

        $user = $this->createUser();

        $transactions = factory(Transaction::class, 5)->create([
            'user_id' => $user->id
        ]);

        $transaction = $transactions[3];

        $this->get("/api/$user->id/transactions/$transaction->tracking_number")
            ->assertJson([
                'data' => [
                    'tracking_number' => $transaction->tracking_number,
                    'amount' => $transaction->amount,
                ]
            ]);
    }

    /**
     * @test
     */
    public function should_cancel_specific_user_transactions() {
        $this->withoutExceptionHandling();

        $user = $this->createUser();

        $transactions = factory(Transaction::class, 5)->create([
            'user_id' => $user->id
        ]);

        $transaction = $transactions[3];

        $this->delete("/api/$user->id/transactions/$transaction->tracking_number")
            ->assertJson([
                'data' => [
                    'tracking_number' => $transaction->tracking_number,
                    'status' => Transaction::FAILED,
                ]
            ]);
    }


    private function createUser() {
        return factory(User::class)->create();
    }
}
