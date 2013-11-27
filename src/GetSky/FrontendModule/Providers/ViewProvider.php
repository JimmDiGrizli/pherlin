<?php
namespace GetSky\FrontendModule\Providers;

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
        $config = $this->options->get('module-options')->get('volt');
        $environment = $this->options->get('app-status')->get('environment');

        return function () use ($config, $environment) {
            $view = new View();
            $view->setViewsDir(__DIR__ . '/../Resources/views/');

            $view->registerEngines(
                array(
                    '.volt' => function ($view) use ($config, $environment) {
                            $volt = new Volt($view);

                            $path = str_replace(
                                "{environment}",
                                $environment,
                                $config->get('path')
                            );

                            $options = array(
                                'compiledPath' => $path,
                                'compiledSeparator' => '_',
                            );

                            if ($config->debug != 1) {
                                $options['compileAlways'] = true;
                            }

                            $volt->setOptions($options);

                            return $volt;
                        },
                    '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
                )
            );

            return $view;
        };
    }
} 