<?php

class RestoUnitTest extends PHPUnit_Framework_TestCase {

    // ...
    public $context;
    public $admin;

    /**
     * @covers RestoContext::__construct
     * @covers RestoUser::__construct
     */
    public function initContext() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $this->context = new RestoContext($config);

        $this->admin = new RestoUser(array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
                ), $this->context);
    }

}
