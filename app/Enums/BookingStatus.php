<?php declare(strict_types=1);

namespace App\Enums;

enum BookingStatus {
    case PENDING;
    case CONFIRMED;
    case CANCELLED;
}
