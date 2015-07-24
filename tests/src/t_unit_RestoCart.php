<?php

class t_unit_RestoUser extends PHPUnit_Framework_TestCase {

    public $__directory = "/home/remi/share/02_TEST/tests";

    // ...

    public function testAdminUser() {

        $config = include($this->__directory . "/conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => 2,
            'email' => 'toto',
            'groups' => 'toto',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);
        $this->assertEquals(2, $user->profile['userid']);

        $cart = new RestoCart($user, false);
        $this->assertEquals('[]', json_encode($cart->getItems()));
        
        $feature = new RestoFeature($context, $user, array(
            'featureIdentifier' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734'
        ));

        $cart->add($feature->toArray(), true);
        
        $this->assertEquals('[todo]', json_encode($cart->getItems()));
    }

}
