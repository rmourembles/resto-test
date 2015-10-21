<?php

/**
 *  Tests for RestoRouteGET class
 */
class t_unit_Admin extends RestoUnitTest {

    // ...

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 403
     */
    public function testAdmin_run_as_non_admin() {
        $this->initContext();
        
        /*
         * Create non admin user
         */
        $profile = array(
            'userid' => 2,
            'groups' => 'default',
            'email' => 'test_email',
            'password' => 'test_password',
            'username' => 'test_username',
            'givenname' => 'test_givenname',
            'lastname' => 'test_lastname',
            'country' => 'FR',
            'organization' => 'test_organization',
            'flags' => null,
            'topics' => null,
            'validatedby' => 'admin',
            'validationdate' => 'now()',
            'activated' => 1
        );
        $user = new RestoUser($profile, $this->context);
        
        $adminModule = new Admin($this->context, $user);
        $segments = array('users');
        $re = $adminModule->run($segments, null);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testAdmin_processGET_error() {
        $this->initContext();
        $this->context->method = 'GET';
        
        $adminModule = new Admin($this->context, $this->admin);
        $segments = array('toto');
        $re = $adminModule->run($segments, null);
    }
    
    public function testAdmin_processGET_users() {
        $this->initContext();
        $this->context->method = 'GET';
        
        $adminModule = new Admin($this->context, $this->admin);
        $segments = array('users');
        $re = $adminModule->run($segments, null);
        
        $this->assertEquals('success', $re['status']);
    }
    
    public function testModuleAdmin() {
        $this->initContext();

        $this->context->method = 'GET';
        $adminModule = new Admin($this->context, $this->admin);

        $_r = $adminModule->run(array('users'), array());
        $this->assertEquals('success', $_r['status']);

        $_r = $adminModule->run(array('history'), array());
        $this->assertEquals('success', $_r['status']);

        $_r = $adminModule->run(array('users', '1'), array());
        $this->assertEquals('success', $_r['status']);

        $_r = $adminModule->run(array('users', '1', 'groups'), array());
        $this->assertEquals('success', $_r['status']);

        $_r = $adminModule->run(array('users', '1', 'rights'), array());
        $this->assertEquals('success', $_r['status']);

        $_r = $adminModule->run(array('users', '1', 'signatures'), array());
        $this->assertEquals('success', $_r['status']);

        $_r = $adminModule->run(array('users', '1', 'orders'), array());
        $this->assertEquals('success', $_r['status']);

        $_r = $adminModule->run(array('users', 'groups'), array());
        $this->assertEquals('success', $_r['status']);
        try {
            $_r = $adminModule->run(array('NQ'), array());
            //$this->fail('An expected exception has not been raised.');
        } catch (Exception $expected) {
            
        }

        $profile = array(
            'userid' => 2,
            'groups' => 'default',
            'email' => 'test_email',
            'password' => 'test_password',
            'username' => 'test_username',
            'givenname' => 'test_givenname',
            'lastname' => 'test_lastname',
            'country' => 'FR',
            'organization' => 'test_organization',
            'flags' => null,
            'topics' => null,
            'validatedby' => 'admin',
            'validationdate' => 'now()',
            'activated' => 1
        );

        $user = new RestoUser($profile, $this->context);
        $adminModule = new Admin($this->context, $user);
        try {
            $_r = $adminModule->run(array('users'), array());
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $expected) {
            
        }

        $this->context->method = 'POST';
        $adminModule = new Admin($this->context, $this->admin);
        try {
            $_r = $adminModule->run(array('NQ'), array());
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $expected) {
            
        }
        try {
            $_r = $adminModule->run(array('licenses', 'NQ'), array());
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $expected) {
            
        }
        try {
            $_r = $adminModule->run(array('licenses'), array());
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $expected) {
            
        }
        try {
            $_r = $adminModule->run(array('users'), array());
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $expected) {
            
        }

        $this->context->method = 'PUT';
        $adminModule = new Admin($this->context, $this->admin);
        try {
            $_r = $adminModule->run(array('NQ'), array());
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $expected) {
            
        }

        $this->context->method = 'DELETE';
        $adminModule = new Admin($this->context, $this->admin);
        try {
            $_r = $adminModule->run(array('NQ'), array());
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $expected) {
            
        }

        $this->context->method = 'NQ';
        $adminModule = new Admin($this->context, $this->admin);
        try {
            $_r = $adminModule->run(array('NQ'), array());
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $expected) {
            
        }
    }
    
}
