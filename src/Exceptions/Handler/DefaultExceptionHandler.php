<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Exceptions\Handler;

use Throwable;
use Tinify\{AccountException, ClientException, ServerException, ConnectionException};
use Vigihdev\WpCliTools\Exceptions\WpCliToolsException;

class DefaultExceptionHandler implements HandlerExceptionInterface
{

    public function handle(Throwable $e): void
    {
        if ($e instanceof AccountException) {
            // Credentials are invalid. (HTTP 401/Unauthorized)
            $this->handleTinify($e->getMessage(), [
                'Periksa apakah API key tinify sudah diatur dengan benar',
                'Cek apakah API key tinify sudah diatur di file wp-config.php',
            ]);
            exit(1);
        }

        if ($e instanceof ClientException) {
            $this->handleTinify($e->getMessage());
            exit(1);
        }

        if ($e instanceof ServerException) {
            $this->handleTinify($e->getMessage());
            exit(1);
        }

        if ($e instanceof ConnectionException) {
            $this->handleTinify($e->getMessage());
            exit(1);
        }

        if ($e instanceof WpCliToolsException) {
            print($e->getMessage());
            echo sprintf("%s", ...$e->getSolutions());
            return;
        }
        print($e->getMessage());
    }

    private function handleTinify(string $message, array $solutions = []): void
    {
        print($message);
        foreach ($solutions as $solution) {
            print("\n");
            print($solution);
        }
    }
}
