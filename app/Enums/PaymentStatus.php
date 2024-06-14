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

    public static function getOptions(): array
    {
        return array_map(fn($role) => ['id' => $role->value, 'name' => $role->name], self::cases());
    }
}
