<?php
namespace GetSky\FrontendModule\Providers;

use GetSky\FrontendModule\Module;
use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Config;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;

class ViewProvider implements Provider
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
     * @return callable
     */
    public function getServices()
    {
        /**
         * @var Config $config
         */
        $config = $this->options
            ->get('module-options')
            ->get(Module::NAME)
            ->get('volt');

        return function () use ($config) {
            $view = new View();
            $view->setViewsDir(Module::DIR . '/Resources/views/');

            $view->registerEngines(
                [
                    '.volt' => function ($view) use ($config) {
                            $volt = new Volt($view);

                            $options = [
                                'compiledPath' => $config->get('path'),
                                'compiledSeparator' => '_',
                            ];

                            if ($config->debug != 1) {
                                $options['compileAlways'] = true;
                            }

                            $volt->setOptions($options);

                            return $volt;
                        },
                    '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
                ]
            );

            return $view;
        };
    }
} 