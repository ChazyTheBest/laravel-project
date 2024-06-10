<?php declare(strict_types=1);

namespace App\Enums;

enum BookingStatus: int {
    case PENDING = 0;
    case CONFIRMED = 1;
    case PAYMENT_FAILED = 2;
    case CANCELLED = 3;
}
