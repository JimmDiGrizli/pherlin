<?php
error_reporting(E_ALL);
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");

require_once '../vendor/autoload.php';

use GetSky\Phalcon\Bootstrap\Bootstrap;
use Phalcon\DI\FactoryDefault;

$app = new Bootstrap(new FactoryDefault(), 'prod');
$app->run(false);

return $app;
