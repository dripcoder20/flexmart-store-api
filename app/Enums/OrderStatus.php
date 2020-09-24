<?php

namespace App\Enums;

class OrderStatus
{
    const CANCELLED = 0;
    const FAILED = 0;
    const SUCCESS = 1;
    const PENDING = 2;
    const PROCESSING = 3;
    const SHIPPED = 4;

    const PENDING_MESSAGE = "Cool! Your order has been created. Our staff will attend to your request right away.";
    const PROCESSING_MESSAGE = "Our staff is already preparing your orders and will be shipped out soon.";
    const SHIPPED_MESSAGE = "Your order is on the way! Our delivery team will attempt to deliver your package.";
    const SUCCESS_MESSAGE = "Your order has been received. Thank you for shopping!";
}
