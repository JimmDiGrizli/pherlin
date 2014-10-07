<?php
error_reporting(0);
ini_set("display_errors", 0);
ini_set("display_startup_errors", 0);

require_once '../vendor/autoload.php';

use GetSky\Phalcon\Bootstrap\Bootstrap;
use Phalcon\DI\FactoryDefault;

echo (new Bootstrap(new FactoryDefault(), 'prod'))->run();