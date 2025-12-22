<?php


error_reporting(-1);

/** @var Composer\Autoload\ClassLoader $autoload  */
$autoload = require __DIR__ . '/../vendor/autoload.php';

$wpBootstrap = __DIR__ . '/wp-bootstrap.php';
if (! file_exists($wpBootstrap)) {
    throw new RuntimeException("Error File Load Not Found {$wpBootstrap}");
}
require $wpBootstrap;
