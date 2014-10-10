<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

require_once '../vendor/autoload.php';

use GetSky\Phalcon\Bootstrap\Bootstrap;
use Phalcon\DI\FactoryDefault;

GetSky\Phalcon\Utils\PrettyExceptions::listenError();
(new \Phalcon\Debug())->listen();

$dic = new FactoryDefault();
$app = new Bootstrap($dic);
$app->setCacheable(false);

echo $app->run();

$debug = $dic->get('debugbar')->getJavascriptRenderer();
$debug->setBaseUrl('//rawgit.com/maximebf/php-debugbar/master/src/DebugBar/Resources/');

echo $debug->renderHead().$debug->render();