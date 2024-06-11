<?php declare(strict_types=1);

namespace App\Enums;

enum PaymentStatus: int {
    case PENDING = 0;
    case SUCCESS = 1;
    case FAILED = 2;

    public static function fromValue(int $value): ?self
    {
        return match ($value) {
            self::PENDING->value => self::PENDING,
            self::SUCCESS->value => self::SUCCESS,
            self::FAILED->value => self::FAILED,
            default => null,
        };
    }
}
