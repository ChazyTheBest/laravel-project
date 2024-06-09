<?php declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus {
    case PENDING;
    case COMPLETED;
    case FAILED;
}
