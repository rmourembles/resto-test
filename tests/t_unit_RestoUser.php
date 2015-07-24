<?php

class t_unit_RestoUser extends PHPUnit_Framework_TestCase {

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
        
        $this->assertEquals(true, $user->isAdmin());
        $this->assertEquals(true, $user->hasRightsTo('create'));
        $this->assertEquals(true, $user->hasRightsTo('download', array('collection' => 'Landsat')));
        $this->assertEquals(true, $user->hasRightsTo('update', array('collection' => 'Landsat')));
        $this->assertEquals(true, $user->hasRightsTo('visualize', array('collection' => 'Landsat')));
        
    }
    
    public function testUnregisteredUser() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => -1,
            'email' => 'unregistered',
            'groups' => 'default',
            'activated' => 0
        );

        $user = new RestoUser($profile, $context);
        
        $this->assertEquals(false, $user->isAdmin());
        $this->assertEquals(false, $user->hasRightsTo('create'));
        $this->assertEquals(false, $user->hasRightsTo('download', array('collection' => 'Landsat')));
        $this->assertEquals(false, $user->hasRightsTo('update', array('collection' => 'Landsat')));
        $this->assertEquals(false, $user->hasRightsTo('visualize', array('collection' => 'Landsat')));
        
    }
    
    public function testRegisteredUser() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => 2,
            'email' => 'toto',
            'groups' => 'default',
            'activated' => 0
        );

        $user = new RestoUser($profile, $context);
        
        
        /*
         * Test when user is not activated
         */
        $this->assertEquals(false, $user->isAdmin());
        $this->assertEquals(false, $user->hasRightsTo('create'));
        $this->assertEquals(false, $user->hasRightsTo('download', array('collection' => 'Landsat')));
        $this->assertEquals(false, $user->hasRightsTo('update', array('collection' => 'Landsat')));
        $this->assertEquals(false, $user->hasRightsTo('visualize', array('collection' => 'Landsat')));
        
        /*
         * Test activation
         */
        $this->assertEquals(true, $user->activate());
        
        
        
        
    }
}