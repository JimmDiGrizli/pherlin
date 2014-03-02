<?php
namespace GetSky\FrontendModule\Providers;

use GetSky\FrontendModule\Module;
use GetSky\Phalcon\AutoloadServices\Provider;
use PDO;
use Phalcon\Config;
use Phalcon\Db\Adapter\Pdo\Mysql;

class MySqlProvider implements Provider
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
        $option = $this->options
            ->get('module-options')
            ->get(Module::NAME)
            ->get('mysql');

        return function () use ($option) {
            $mysql = new Mysql(
                [
                    'host' => $option->host,
                    'username' => $option->username,
                    'password' => $option->password,
                    'persistent' => $option->persistent,
                    'dbname' => $option->name,
                    'options' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ]
                ]
            );

            return $mysql;
        };
    }
} 