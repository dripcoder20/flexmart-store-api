<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    protected $guarded = [];

    // Transaction Types
    const DEBIT = 1;
    const CREDIT = 2;

    // Transaction Status
    const SUCCESS = 1;
    const FAILED = 2;
    const REFUND = 3;
    const AWAITING_PAYMENT = 4;
    const PREPARING = 5;
    const TO_SHIP = 6;
    const TO_RECEIVE = 7;

    public static function boot() {
        parent::boot();

        self::creating(function($transaction)
        {
            // TODO: Create a tracking number generator
            $transaction->tracking_number = Str::uuid();
        });
    }

    public static function collection($userId, $offset, $limit)
    {
        return self::where('user_id', $userId)
            ->skip($offset)
            ->take($limit)
            ->get();
    }

    public static function find($userId, $trackingNumber) {
        return self::where('user_id', $userId)
            ->where('tracking_number', $trackingNumber)
            ->first();
    }

    public static function cancel($userId, $trackingNumber) {

        $transaction = self::find($userId, $trackingNumber);
        $transaction->update(['status' => self::FAILED]);

        return $transaction->fresh();
    }

    // TODO: Add check out or create transaction method
}
