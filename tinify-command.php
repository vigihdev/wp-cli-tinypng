<?php

use Vigihdev\WpCliTinypng\Command\{
    AddKey_Tinify_Command,
    Convert_Tinify_Command,
    Resize_Tinify_Command,
    Tinify_Command
};

if (! class_exists('WP_CLI')) {
    return;
}

$autoloader = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
}

// Add commands
WP_CLI::add_command('tini', new Tinify_Command());
WP_CLI::add_command('tini:add-key', new AddKey_Tinify_Command());
WP_CLI::add_command('tini:convert', new Convert_Tinify_Command());
WP_CLI::add_command('tini:resize', new Resize_Tinify_Command());
