<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Command;

use Symfony\Component\Filesystem\Path;
use WP_CLI_Command;
use Vigihdev\WpCliModels\Exceptions\Handler\HandlerExceptionInterface;
use Vigihdev\WpCliModels\Exceptions\Handler\WpCliExceptionHandler;
use Vigihdev\WpCliModels\UI\CliStyle;

abstract class Tinify_Base_Command extends WP_CLI_Command
{
    protected ?int $width = null;
    protected ?int $height = null;
    protected string $output = '';
    protected string $filepath = '';

    /**
     * @var CliStyle $io
     */
    protected ?CliStyle $io = null;

    protected HandlerExceptionInterface $exceptionHandler;

    public function __construct(
        protected string $name
    ) {
        parent::__construct();
        $this->exceptionHandler = new WpCliExceptionHandler();

        if (!$this->io) {
            $this->io = new CliStyle();
        }
    }

    protected function normalizeFilePath(): self
    {

        $this->filepath = Path::isAbsolute($this->filepath) ?
            $this->filepath : Path::join(getcwd() ?? '', $this->filepath);
        return $this;
    }

    protected function normalizeOutput(): self
    {
        $this->output = Path::isAbsolute($this->output) ?
            $this->output : Path::join(getcwd() ?? '', $this->output);
        return $this;
    }
}
