<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Path;

error_reporting(-1);

// Wp Load and Cli
$pathWpInstallation = Path::join(getenv('HOME') ?? '', 'Sites', 'okkarent-group', 'omahtrans');
$fileWpInstallations = Path::join($pathWpInstallation, 'wp-load.php');
$fileCliAutoLoad = Path::join(getenv('HOME') ?? '', 'VigihDev', 'PackagistDev', 'wp-cli-dev', 'vendor', 'autoload.php');

if (! file_exists($fileCliAutoLoad)) {
    throw new RuntimeException("Error File Load Not Found {$fileWpInstallations}");
}


if (! file_exists($fileCliAutoLoad)) {
    throw new RuntimeException("Error File Load Not Found {$fileCliAutoLoad}");
}

require $fileWpInstallations;
require $fileCliAutoLoad;

// Dotenv 
$dotEnv = new Dotenv();
$dotEnv->usePutenv(true);
