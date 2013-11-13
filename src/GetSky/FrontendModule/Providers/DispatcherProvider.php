<?php
namespace GetSky\FrontendModule\Providers;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Mvc\Dispatcher;

class DispatcherProvider implements Provider {

    /**
     * @return callable
     */
    public function getServices()
    {
        return function() {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace("GetSky\\FrontendModule\\Controllers");
            return $dispatcher;
        };
    }
} 