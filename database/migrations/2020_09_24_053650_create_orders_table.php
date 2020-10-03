<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Enums\ShippingType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->default(0);
            $table->string('user_id');
            $table->json('user')->nullable();
            $table->enum('payment_type', [
                PaymentType::CASH_ON_DELIVERY,
                PaymentType::PAYMENT_GATEWAY,
                PaymentType::CREDIT_CARD
              ])->default(PaymentType::CASH_ON_DELIVERY);

            $table->enum('shipping_type', [
                ShippingType::FREE,
                ShippingType::STANDARD,
                ShippingType::EXPRESS,
                ShippingType::PICKUP,
                ShippingType::ONLINE,
            ])->default(ShippingType::STANDARD);
            $table->unsignedDouble('amount');
            $table->unsignedDouble('shipping_charge')->default(0);
            $table->json('shipping_information');
            $table->unsignedDouble('discount')->default(0);
            $table->json('cart');
            $table->enum('status', [
                OrderStatus::PENDING,
                OrderStatus::FAILED,
                OrderStatus::SUCCESS,
                OrderStatus::PROCESSING,
                OrderStatus::SHIPPED
            ])->default(OrderStatus::PENDING);
            $table->boolean('payment_received')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
