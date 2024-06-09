<?php declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: int {
    case PENDING = 0;
    case COMPLETED = 1;
    case FAILED = 2;
}
