<?php
namespace GetSky\FrontendModule\Providers;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Mvc\View;

class ViewProvider implements Provider {

    /**
     * @return mixed
     */
    public function getServices()
    {
        return function() {
            $view = new View();
            $view->setViewsDir('/Resources/views/');
            return $view;
        };
    }
} 