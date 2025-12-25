<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Enums;

enum ResizeMethodEnum: string
{
    case SCALE = 'scale';
    case FIT = 'fit';
    case COVER = 'cover';
    case THUMB = 'thumb';

    public function isScale(): bool
    {
        return $this === self::SCALE;
    }

    public function isFit(): bool
    {
        return $this === self::FIT;
    }

    public function isCover(): bool
    {
        return $this === self::COVER;
    }

    public function isThumb(): bool
    {
        return $this === self::THUMB;
    }

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
