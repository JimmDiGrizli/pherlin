<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

require_once '../vendor/autoload.php';

use DebugBar\StandardDebugBar;
use GetSky\Phalcon\Bootstrap\Bootstrap;
use Phalcon\DI\FactoryDefault;

GetSky\Phalcon\Utils\PrettyExceptions::listenError();
(new \Phalcon\Debug())->listen();
$debug = (new StandardDebugBar())->getJavascriptRenderer();
$debug->setBaseUrl('//rawgit.com/maximebf/php-debugbar/master/src/DebugBar/Resources/');

$app = new Bootstrap(new FactoryDefault());
$app->setCacheable(false);

echo $app->run().$debug->renderHead().$debug->render();