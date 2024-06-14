<?php declare(strict_types=1);

namespace App\Enums;

enum Role: int {
    case USER = 0;
    case STAFF = 1;

    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getOptions(): array
    {
        return array_map(fn($role) => ['id' => $role->value, 'name' => $role->name], self::cases());
    }
}
