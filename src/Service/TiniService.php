<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Service;

use Throwable;
use Tinify\Source;
use Tinify\Tinify;
use function Tinify\validate;
use Vigihdev\WpCliTinypng\Contracts\ConnectionInterface;
use Vigihdev\WpCliTinypng\Exceptions\TinifyException;
use Vigihdev\WpCliTinypng\Exceptions\Handler\{DefaultExceptionHandler, HandlerExceptionInterface};

final class TiniService implements ConnectionInterface
{
    private Tinify $tinify;

    private bool $isInitialized = false;
    private HandlerExceptionInterface $exceptionHandler;

    /**
     * @param string $apiKey
     * @throws TinifyException Jika API key tidak valid
     */
    public function __construct(
        private readonly string $apiKey
    ) {
        $this->exceptionHandler = new DefaultExceptionHandler();
        $this->initialize();
    }

    /**
     * Initialize Tinify client
     * @throws TinifyException
     */
    private function initialize(): void
    {
        if (!$this->isInitialized) {
            $this->tinify = new Tinify();
            $this->tinify::setKey($this->apiKey);
            $this->isInitialized = true;
        }
    }

    public function getCompressionCount(): int
    {
        $this->validateInitialization();
        return $this->tinify::getCompressionCount();
    }

    public function fromFile(string $path): Source
    {
        $this->validateInitialization();
        return Source::fromFile($path);
    }

    public function fromBuffer(string $string): Source
    {
        $this->validateInitialization();
        return Source::fromBuffer($string);
    }

    public function fromUrl(string $url): Source
    {
        $this->validateInitialization();
        return Source::fromUrl($url);
    }

    public function connection(): bool
    {
        try {
            $this->validateInitialization();
            return (bool) validate();
        } catch (Throwable $e) {
            $this->exceptionHandler->handle($e);
        }
    }

    /**
     * Validasi apakah Tinify sudah diinisialisasi
     * @throws TinifyException
     */
    private function validateInitialization(): void
    {
        if (!$this->isInitialized) {
            throw TinifyException::notInitialization();
        }
    }

    /**
     * Get remaining compressions this month
     */
    public function getRemainingCompressions(): int
    {
        $this->validateInitialization();
        $used = $this->getCompressionCount();
        return max(0, 500 - $used);
    }
}
