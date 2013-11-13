<?php
namespace App\Providers;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Config;
use Phalcon\Mvc\Router;

class RouterProvider implements Provider
{

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
        $modules = $this->options->get('modules');

        return function () use ($default, $modules) {

            $router = new \Phalcon\Mvc\Router();

            foreach ($modules as $name => $module) {

                if ($default == $name) {
                    $router->setDefaultModule($default);
                    continue;
                }

                $router->add('#^/'.$name.'(|/)$#', array(
                        'module' => $name,
                        'controller' => 'index',
                        'action' => 'index',
                    ));

                $router->add(
                    '#^/'.$name.'/([a-zA-Z0-9\_]+)[/]{0,1}$#',
                    array(
                        'module' => $name,
                        'controller' => 1,
                    )
                );

                $router->add(
                    '#^/'.$name.'[/]{0,1}([a-zA-Z0-9\_]+)/([a-zA-Z0-9\_]+)(/.*)*$#',
                    array(
                        'module' => $name,
                        'controller' => 1,
                        'action' => 2,
                        'params' => 3,
                    )
                );
            }

            return $router;
        };
    }
}