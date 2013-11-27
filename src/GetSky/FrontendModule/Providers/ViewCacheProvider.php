<?php
namespace GetSky\FrontendModule\Providers;

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
        $config = $this->options->get('module-options')->get('volt');
        $environment = $this->options->get('app-status')->get('environment');

        return function () use ($config, $environment) {

            $ultraFastFrontend = new Data(array("lifetime" => 3600));
            $fastFrontend = new Data(array("lifetime" => 86400));
            $slowFrontend = new Data(array("lifetime" => 604800));

            $path = str_replace(
                "{environment}",
                $environment,
                $config->get('path')
            );

            $cache = new Multiple(
                array(
                    new Apc(
                        $ultraFastFrontend,
                        array("prefix" => 'cache')
                    ),
                    new Memcache(
                        $fastFrontend,
                        array(
                            "prefix" => 'cache',
                            "host" => $config->get("host"),
                            "port" => $config->get("port")
                        )
                    ),
                    new File(
                        $slowFrontend,
                        array(
                            "prefix" => 'cache',
                            "cacheDir" => $path
                        )
                    )
                )
            );

            return $cache;
        };
    }
}