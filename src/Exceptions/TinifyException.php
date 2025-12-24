<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Exceptions;

final class TinifyException extends BaseException
{

    public static function notInitialization(): self
    {
        return new self(
            message: 'Tinify not initialized',
            code: 400,
            solutions: [
                'Check if Tinify is initialized',
            ],
        );
    }
}
