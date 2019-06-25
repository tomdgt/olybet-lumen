<?php
use Composer\Autoload\ClassLoader;

include_once __DIR__.'/../bootstrap/app.php';

$classLoader = new ClassLoader();
$classLoader->addPsr4("Olybet\\Tests\\", __DIR__, true);
$classLoader->register();
