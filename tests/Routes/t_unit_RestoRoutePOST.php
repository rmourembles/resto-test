<?php

class t_unit_RestoRoutePOST extends PHPUnit_Framework_TestCase {

    // ...

    public function testAdminUser() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);
        $routePOST = new RestoRoutePOST($context, $user);
        
        $data = array(
            'email' => 'toto',
            'password' => 'toto'
        );
        
        $routePOST->createUser($data);
        
    }
    
    
}