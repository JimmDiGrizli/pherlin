<?php
error_reporting(E_ALL);

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