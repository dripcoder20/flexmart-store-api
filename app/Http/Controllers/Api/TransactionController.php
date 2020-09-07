<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransactionController extends Controller
{
    public function index($userId)
    {
        $transactions = Transaction::collection(
            $userId,
            request('offset', 0),
            request('limit', 10)
        );
        return TransactionResource::collection($transactions);
    }

    public function show($userId, $trackingNumber)
    {
        $transaction = Transaction::find($userId, $trackingNumber);
        return new TransactionResource($transaction);
    }

    public function destroy($userId, $trackingNumber)
    {
        $transaction = new TransactionResource(
            Transaction::cancel($userId, $trackingNumber)
        );

        return response()->json([
            'message' => 'Transaction was cancelled',
            'data' => $transaction->resource->toArray()
        ], Response::HTTP_ACCEPTED);
    }

    /**
     * TODO: Add route for admin with appropriate guard
     * @codeCoverageIgnore
     * @ignore Codeception specific
     */
    public function update($trackingNumber, Request $request)
    {
        $transaction = Transaction::find('tracking_number', $trackingNumber);

        $transaction->update($request->all());

        return response()->json([
            'message' => 'Transaction was updated',
            'data' => $transaction->toArray()
        ], Response::HTTP_ACCEPTED);
    }
}
