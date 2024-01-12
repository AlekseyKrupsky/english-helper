<?php

namespace App\Enum;

enum TermType: string
{
    case EN = 'en';
    case RU = 'ru';

    public static function casesExceptOne(TermType $exceptionType): array
    {
        $allTypes = TermType::cases();

        return array_filter($allTypes, function ($type) use ($exceptionType) {
            return $type !== $exceptionType;
        });
    }
}
