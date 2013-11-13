<?php
namespace GetSky\FrontendModule;

use Phalcon\Config\Adapter\Ini;
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;
use GetSky\Phalcon\AutoloadServices\Registrant;

class Module implements ModuleDefinitionInterface {

    /**
     * Registers an autoloader related to the module
     *
     */
    public function registerAutoloaders()
    {
        $loader = new Loader();

        $loader->registerNamespaces(
            array(
                'GetSky\FrontendModule' => '../src/GetSky/FrontendModule/'
            )
        );

        $loader->register();
    }

    /**
     * Registers an autoloader related to the module
     *
     * @param \Phalcon\DiInterface $dependencyInjector
     */
    public function registerServices($dependencyInjector)
    {
        $dependencyInjector->setShared(
            'module-options',
            new Ini('/Resources/config/options.ini')
        );

        /**
         * @var Registrant $registrant
         */
        $registrant = $dependencyInjector->get('registrant');
        $registrant->setServices(new Ini('/Resources/config/services.ini'));
        $registrant->registration();
    }
}