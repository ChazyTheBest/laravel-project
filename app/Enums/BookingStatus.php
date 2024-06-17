<?php declare(strict_types=1);

namespace App\Enums;

enum BookingStatus: int {
    case PENDING = 0;
    case CONFIRMED = 1;
    case PAYMENT_FAILED = 2;
    case CANCELED = 3;

    public static function getStatusText(BookingStatus $status): string
    {
        return match ($status->value) {
            self::PENDING->value => 'Pending',
            self::CONFIRMED->value => 'Confirmed',
            self::PAYMENT_FAILED->value => 'Payment Failed',
            self::CANCELED->value => 'Canceled',
            default => 'Unknown',
        };
    }

    public static function getOptions(): array
    {
        return array_map(fn($role) => ['id' => $role->value, 'name' => $role->name], self::cases());
    }
}
