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

    public static function notValidResizeMethod(string $resize, array $resizes): self
    {
        return new self(
            message: sprintf('Resize method %s is not valid', $resize),
            code: 400,
            context: [
                'resize' => $resize,
                'valid' => $resizes,
            ],
            solutions: [
                'Check if resize method is valid',
                sprintf('Valid resize methods are %s', implode(', ', $resizes)),
            ],
        );
    }

    public static function notValidWidth(int $width): self
    {
        return new self(
            message: sprintf('Width %d is not valid', $width),
            code: 400,
            context: [
                'width' => $width,
                'valid' => "width must be more than 0",
            ],
            solutions: [
                'Check if width is valid',
                'Width must be more than 0',
            ],
        );
    }

    public static function notValidHeight(int $height): self
    {
        return new self(
            message: sprintf('Height %d is not valid', $height),
            code: 400,
            context: [
                'height' => $height,
                'valid' => "height must be more than 0",
            ],
            solutions: [
                'Check if height is valid',
                'Height must be more than 0',
            ],
        );
    }
}
