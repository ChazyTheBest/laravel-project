<?php declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: int {
    case PENDING = 0;
    case SUCCESS = 1;
    case FAILED = 2;
}
