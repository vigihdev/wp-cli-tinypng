<?php

use Vigihdev\WpCliTinypng\Command\Tinify_Command;

if (! class_exists('WP_CLI')) {
    return;
}

$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
}

// Add commands
WP_CLI::add_command('tinify', new Tinify_Command());
