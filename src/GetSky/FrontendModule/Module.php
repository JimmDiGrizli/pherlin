<?php
namespace GetSky\FrontendModule;

use Phalcon\Config;
use Phalcon\Loader;
use \GetSky\Phalcon\Bootstrap\Module  as ModuleBootstrap;

class Module extends ModuleBootstrap
{
    protected $dir = __DIR__;
}