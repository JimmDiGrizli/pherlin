<?php
namespace GetSky\FrontendModule\Providers;

use GetSky\FrontendModule\Module;
use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Cache\Backend\Apc;
use Phalcon\Cache\Backend\File;
use Phalcon\Cache\Backend\Memcache;
use Phalcon\Cache\Frontend\Data;
use Phalcon\Cache\Multiple;
use Phalcon\Config;

class ViewCacheProvider implements Provider
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
            ->get('cache');

        return function () use ($config) {

            $ultraFastFrontend = new Data(['lifetime' => 3600]);
            $fastFrontend = new Data(['lifetime' => 86400]);
            $slowFrontend = new Data(['lifetime' => 604800]);

            $cache = new Multiple(
                [
                    new Apc(
                        $ultraFastFrontend,
                        ['prefix' => 'cache']
                    ),
                    new Memcache(
                        $fastFrontend,
                        [
                            'prefix' => 'cache',
                            'host' => $config->get('host'),
                            'port' => $config->get('port')
                        ]
                    ),
                    new File(
                        $slowFrontend,
                        [
                            'prefix' => 'cache',
                            'cacheDir' => $config->get('path')
                        ]
                    )
                ]
            );

            return $cache;
        };
    }
}