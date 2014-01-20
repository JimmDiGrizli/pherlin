<?php
error_reporting(E_ALL);
ini_set("display_errors","0");
ini_set("display_startup_errors","0");

require_once '../vendor/autoload.php';

use GetSky\Phalcon\Bootstrap\Bootstrap;
use Phalcon\DI\FactoryDefault;

try {
    $app = new Bootstrap(new FactoryDefault());
    echo $app->run();
} catch (Phalcon\Exception $e) {
    echo $e->getMessage() . '<br />';
    echo $e->getTraceAsString();
} catch (PDOException $e) {
    echo $e->getMessage();
}