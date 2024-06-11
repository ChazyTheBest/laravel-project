<?php declare(strict_types=1);

namespace App\Enums;

enum BookingStatus: int {
    case PENDING = 0;
    case CONFIRMED = 1;
    case PAYMENT_FAILED = 2;
    case CANCELED = 3;

    public static function fromValue(int $value): ?self
    {
        return match ($value) {
            self::PENDING->value => self::PENDING,
            self::CONFIRMED->value => self::CONFIRMED,
            self::PAYMENT_FAILED->value => self::PAYMENT_FAILED,
            self::CANCELED->value => self::CANCELED,
            default => null,
        };
    }

    public static function getStatusText(BookingStatus $status)
    {
        return match ($status->value) {
            self::PENDING->value => 'Pending',
            self::CONFIRMED->value => 'Confirmed',
            self::PAYMENT_FAILED->value => 'Payment Failed',
            self::CANCELED->value => 'Canceled',
            default => 'Unknown',
        };
    }
}
