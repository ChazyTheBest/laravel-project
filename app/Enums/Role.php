<?php declare(strict_types=1);

namespace App\Enums;

enum Role: int {
    case USER = 0;
    case STAFF = 1;
}
