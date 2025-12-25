<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Exceptions\Handler;

use Throwable;
use Tinify\{AccountException, ClientException, ServerException, ConnectionException};
use Vigihdev\WpCliTinypng\Exceptions\BaseException;
use Vigihdev\WpCliTools\Exceptions\WpCliToolsException;
use Vigihdev\WpCliTools\Exceptions\Handler\DefaultExceptionHandler as WpCliToolsExceptionHandler;
use WP_CLI;

class DefaultExceptionHandler implements HandlerExceptionInterface
{

    public function handle(Throwable $e): void
    {
        if ($e instanceof AccountException) {
            // Credentials are invalid. (HTTP 401/Unauthorized)
            $this->handleTinify($e->getMessage(), [
                'api_key' => 'invalid',
                'Waktu' => date('Y-m-d H:i:s'),
            ], [
                'Periksa apakah API key tinify sudah diatur dengan benar',
                'Cek apakah API key tinify sudah diatur di file wp-config.php',
                'Pastikan tidak melebihi batas permintaan bulanan',
            ]);
            exit(1);
        }

        if ($e instanceof ClientException) {
            $this->handleTinify($e->getMessage(), [
                'Waktu' => date('Y-m-d H:i:s'),
            ], [
                'Periksa format file gambar yang akan dikompresi',
                'Pastikan ukuran file tidak melebihi batas maksimum',
                'Coba kembali dalam beberapa saat',
            ]);
            exit(1);
        }

        if ($e instanceof ServerException) {
            $this->handleTinify($e->getMessage(), [
                'Waktu' => date('Y-m-d H:i:s'),
            ], [
                'Tunggu beberapa saat sebelum mencoba kembali',
                'Coba kembali dalam beberapa menit',
            ]);
            exit(1);
        }

        if ($e instanceof ConnectionException) {
            $this->handleTinify($e->getMessage(), [
                'Waktu' => date('Y-m-d H:i:s'),
            ], [
                'Periksa koneksi internet Anda',
                'Pastikan tidak ada firewall yang memblokir koneksi',
                'Coba kembali dalam beberapa saat',
            ]);
            exit(1);
        }

        if ($e instanceof WpCliToolsException) {
            $handle = new WpCliToolsExceptionHandler();
            $handle->handle($e);
            exit(1);
        }

        if ($e instanceof BaseException) {
            $this->handleTinify($e->getMessage(), $e->getContext(), $e->getSolutions());
            exit(1);
        }

        WP_CLI::error($e->getMessage());
    }

    private function handleTinify(string $message, array $context = [], array $solutions = []): void
    {

        $message = sprintf("❌ %s", $message);
        $paddingLeft = str_repeat(' ', 5);

        // Jika ada konteks, tambahkan ke pesan
        if ($this->isAssociativeArray($context) && count($context) > 0) {
            $maxLabelLength = max(array_map('strlen', array_keys($context)));
            foreach ($context as $key => $value) {
                $value = is_array($value) ? implode(', ', $value) : (string) $value;
                $value = WP_CLI::colorize("%b{$value}%n");
                $padding = str_repeat(' ', $maxLabelLength - strlen($key));

                $message .= "\n";
                $message .= sprintf("%s%s:%s %s", $paddingLeft, $key, $padding, $value);
            }
        }

        if ($this->isIndexedArray($solutions) && count($solutions) > 0) {
            $message .= "\n\n";
            $message .= sprintf("%s%s", $paddingLeft, WP_CLI::colorize("%GSaran:%n"));
            foreach ($solutions as $solution) {
                $message .= "\n";
                $message .= sprintf("%s   %s", $paddingLeft, WP_CLI::colorize("%g{$solution}%n"));
            }
        }
        WP_CLI::error($message);
    }

    private function isAssociativeArray(array $data): bool
    {
        if ($data === []) {
            return false;
        }

        return array_keys($data) !== range(0, count($data) - 1);
    }

    private function isIndexedArray(array $data): bool
    {
        return !$this->isAssociativeArray($data);
    }
}
