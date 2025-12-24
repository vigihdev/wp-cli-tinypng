<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Command;

use Symfony\Component\Filesystem\Path;
use WP_CLI_Command;
use Vigihdev\WpCliTools\Exceptions\Handler\{HandlerExceptionInterface, DefaultExceptionHandler};
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliTools\Builders\ImageSizeBuilder;

abstract class Tinify_Base_Command extends WP_CLI_Command
{
    protected const ALLOW_EXTENSION = ['png', 'jpg', 'jpeg', 'webp'];
    protected ?int $width = null;
    protected ?int $height = null;
    protected string $output = '';
    protected string $filepath = '';
    protected bool $force = false;

    /**
     * @var CliStyle $io
     */
    protected ?CliStyle $io = null;

    protected HandlerExceptionInterface $exceptionHandler;

    public function __construct(
        protected string $name
    ) {
        parent::__construct();
        $this->exceptionHandler = new DefaultExceptionHandler();

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

    protected function imageBuilder(): ImageSizeBuilder
    {
        $builder = new ImageSizeBuilder($this->filepath);
        return $builder;
    }
}
