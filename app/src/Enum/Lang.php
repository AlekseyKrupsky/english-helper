<?php

namespace App\Enum;

enum Lang: string
{
    case EN = 'en';
    case RU = 'ru';

    public static function casesExceptOne(Lang $exceptionType): array
    {
        $allTypes = Lang::cases();

        return array_filter($allTypes, function ($type) use ($exceptionType) {
            return $type !== $exceptionType;
        });
    }
}
