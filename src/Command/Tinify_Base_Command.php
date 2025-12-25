<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Command;

use Symfony\Component\Filesystem\Path;
use WP_CLI_Command;
use Vigihdev\WpCliTinypng\Exceptions\Handler\{HandlerExceptionInterface, DefaultExceptionHandler};
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliTinypng\Validators\TiniValidator;
use Vigihdev\WpCliTools\Builders\ImageSizeBuilder;
use Vigihdev\WpCliTools\Support\ImageSizeCalculator;
use Vigihdev\WpCliTools\Validators\DirectoryValidator;
use Vigihdev\WpCliTools\Validators\FileValidator;

abstract class Tinify_Base_Command extends WP_CLI_Command
{
    protected const ALLOW_EXTENSION = ['png', 'jpg', 'jpeg', 'webp'];
    protected ?int $width = null;
    protected ?int $height = null;
    protected string $output = '';
    protected string $filepath = '';
    protected string $resize = 'fit';
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

    protected function calculatorWidthAndHeight(): void
    {
        $imageSize = new ImageSizeCalculator($this->filepath);
        if ($this->width === 0 && $this->height > 0) {
            $dimension = $imageSize->toHeight($this->width);
            $this->width = $dimension->getWidth();
            $this->height = $dimension->getHeight();
        }

        if ($this->height === 0 && $this->width > 0) {
            $dimension = $imageSize->toWidth($this->width);
            $this->width = $dimension->getWidth();
            $this->height = $dimension->getHeight();
        }
    }

    protected function validateFilepath(): void
    {
        FileValidator::validate($this->filepath)
            ->mustExist()
            ->mustBeMimeType()
            ->mustBeExtension(self::ALLOW_EXTENSION);
    }

    protected function validateOutput()
    {
        $directory = Path::getDirectory($this->output);
        DirectoryValidator::validate($directory)
            ->mustExist()
            ->mustBeWritable();
    }

    protected function validataTiniResize()
    {
        TiniValidator::validate()
            ->mustBeResizeMethod($this->resize)
            ->mustBeWidthMoreThanZero($this->width)
            ->mustBeHeightMoreThanZero($this->height);
    }

    private function validata() {}
}
