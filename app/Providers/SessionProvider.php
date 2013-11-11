<?php
namespace App\Providers;

use GetSky\Phalcon\AutoloadServices\Provider;
use Phalcon\Session\Adapter\Files;

class SessionProvider implements Provider {

    /**
     * @return mixed
     */
    public function getServices()
    {
        return function() {
            $session = new Files();
            $session->start();
            return $session;
        };
    }
} 