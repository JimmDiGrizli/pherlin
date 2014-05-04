<?php
use Phalcon\Config;
use Phalcon\DI;
use Phalcon\DiInterface;
use \Phalcon\Test\UnitTestCase as PhalconTestCase;

abstract class UnitTestCase extends PhalconTestCase {

    /**
     * @var \Phalcon\Config
     */
    protected $_config;

    /**
     * @var bool
     */
    private $_loaded = false;

    public function setUp(
        DiInterface $di = null,
        Config $config = null
    ) {
        $di = DI::getDefault();
        parent::setUp($di);

        $this->_loaded = true;
    }

    /**
     * @throws PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct() {
        if(!$this->_loaded) {
            throw new PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }
    }
}