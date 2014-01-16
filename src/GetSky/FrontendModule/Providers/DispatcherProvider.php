<?php
namespace GetSky\FrontendModule\Providers;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Config;
use Phalcon\Events\Manager;
use Phalcon\Mvc\Dispatcher;

class DispatcherProvider implements Provider
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
        $option = $this->options->get('errors')->get('404');

        return function () use ($option) {

            $eventsManager = new Manager();

            $eventsManager->attach(
                "dispatch:beforeException",
                function ($event, $dispatcher, $exception) use ($option) {
                    switch ($exception->getCode()) {
                        case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                        case Dispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $dispatcher->forward(
                                array(
                                    'controller' => $option->get('controller'),
                                    'action' => $option->get('action')
                                )
                            );
                            return false;
                    }
                }
            );

            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($eventsManager);
            $dispatcher->setDefaultNamespace(
                'GetSky\FrontendModule\Controllers'
            );
            return $dispatcher;
        };
    }
} 