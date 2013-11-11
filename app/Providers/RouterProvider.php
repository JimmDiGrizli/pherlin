<?php
namespace App\Providers;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Config;
use Phalcon\Mvc\Router;

class RouterProvider implements Provider {

    /**
     * @var Config
     */
    private $options;

    public function __construct(Config $options)
    {
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getServices()
    {
        $default = $this->options->get('app')->get('def_module');

        return function() use ($default) {

            $router = new Router();
            $router->setDefaultModule($default);

            return $router;
        };
    }
}