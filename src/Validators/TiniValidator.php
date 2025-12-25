<?php

namespace Vigihdev\WpCliTinypng\Validators;

use Vigihdev\WpCliTinypng\Enums\ResizeMethodEnum;
use Vigihdev\WpCliTinypng\Exceptions\TinifyException;

final class TiniValidator
{
    public static function validate(): self
    {
        return new self();
    }

    /**
     * Memastikan width lebih besar dari 0
     *
     * @param int $width
     * @return self
     */
    public function mustBeWidthMoreThanZero(int $width): self
    {
        if ($width <= 0) {
            throw TinifyException::notValidWidth($width);
        }

        return $this;
    }

    /**
     * Memastikan height lebih besar dari 0
     *
     * @param int $height
     * @return self
     */
    public function mustBeHeightMoreThanZero(int $height): self
    {
        if ($height <= 0) {
            throw TinifyException::notValidHeight($height);
        }

        return $this;
    }

    /**
     * Memastikan resize method valid
     *
     * @param string $resize
     * @return self
     */
    public function mustBeResizeMethod(string $resize): self
    {
        $resizes = ResizeMethodEnum::toArray();
        if (!in_array($resize, $resizes)) {
            throw TinifyException::notValidResizeMethod($resize, $resizes);
        }
        return $this;
    }
}
