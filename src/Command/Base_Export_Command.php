<?php

declare(strict_types=1);

namespace Vigihdev\WpCliTinypng\Command;

use WP_CLI_Command;
use Vigihdev\WpCliModels\Exceptions\Handler\HandlerExceptionInterface;
use Vigihdev\WpCliModels\Exceptions\Handler\WpCliExceptionHandler;

abstract class Base_Export_Command extends WP_CLI_Command
{

    protected HandlerExceptionInterface $exceptionHandler;
    public function __construct(
        protected string $name
    ) {
        parent::__construct();
        $this->exceptionHandler = new WpCliExceptionHandler();
    }
}
