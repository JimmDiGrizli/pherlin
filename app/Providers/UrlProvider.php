<?php
namespace App\Providers;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Config;
use Phalcon\Mvc\Url;

class UrlProvider implements Provider {

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
        $default = $this->options->get('app')->get('base_uri');

        return function() use ($default) {
            $url = new Url();
            $url->setBaseUri($default);
            return $url;
        };
    }
} 