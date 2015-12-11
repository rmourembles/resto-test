<?php

/**
 * Class to clean testing datatbase
 */
class t_unit_delete extends RestoUnitTest {

    // ...
    
    /**
     * From route : trying to delete without collection name
     * 
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteDELETE_collections_error_1() {
        $this->initContext();
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $this->admin);
        $segments = array('collections');
        $res = $restoRouteDELETE->route($segments);
    }
    
    /**
     * From route : trying to delete a non existing feature
     * 
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteDELETE_collections_error_2() {
        $this->initContext();
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $this->admin);
        $segments = array('collections', 'Landsat', 'toto');
        $res = $restoRouteDELETE->route($segments);
    }
    
    /**
     * From route : trying to delete with too much segments
     * 
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteDELETE_collections_error_3() {
        $this->initContext();
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $this->admin);
        $segments = array('collections', 'Landsat', 'toto', 'titi');
        $res = $restoRouteDELETE->route($segments);
    }
    
    /**
     * From route : trying to delete with wrong segment
     * 
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteDELETE_collections_error_4() {
        $this->initContext();
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $this->admin);
        $segments = array('toto');
        $res = $restoRouteDELETE->route($segments);
    }
    
    /**
     * From route : Try to delete resource without being administrator
     * 
     * @expectedException              Exception
     * @expectedExceptionCode 403
     */
    public function testRestoRouteDELETE_collections_error_5() {
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
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $user);
        $segments = array('collections', 'Landsat');
        $res = $restoRouteDELETE->route($segments);
    }
    
    /**
     * From route : trying to delete user cart with wrong segment
     * 
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteDELETE_user_error_4() {
        $this->initContext();
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $this->admin);
        $segments = array('user');
        $res = $restoRouteDELETE->route($segments);
    }
    
    /*
     * From route : delete admin user cart
     */
    public function testRestoRouteDELETE_user_cart() {
        $this->initContext();
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $this->admin);
        $segments = array('user', 'cart');
        $res = $restoRouteDELETE->route($segments);
        $this->assertEquals('success', $res['status']);
    }
    
    /**
     * From route : trying to remove item from user cart with wrong id
     */
    public function testRestoRouteDELETE_user_cart_error() {
        $this->initContext();
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $this->admin);
        $segments = array('user', 'cart', 'toto');
        $res = $restoRouteDELETE->route($segments);
        $this->assertEquals('error', $res['status']);
    }
    
    /*
     * From route : delete collection
     */
    public function testDELETE_collection_feature() {
        $this->initContext();
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $this->admin);
        $segments = array('collections', 'Landsat', 'c5dc1f32-002d-5ee9-bd4a-c690461eb734');
        $res = $restoRouteDELETE->route($segments);
        $this->assertEquals('success', $res['status']);
    }
    
    /*
     * From route : delete collection
     */
    public function testDELETE_collection() {
        $this->initContext();
        
        $restoRouteDELETE = new RestoRouteDELETE($this->context, $this->admin);
        $segments = array('collections', 'Landsat');
        $res = $restoRouteDELETE->route($segments);
        $this->assertEquals('success', $res['status']);
    }

    /*
     * From api : remove collection
     */
    public function testRemoveCollection() {
        $this->initContext();
        $collection = new RestoCollection('Example', $this->context, $this->admin, array('autoload' => true));
        $collection->removeFromStore();
        
        $collection = new RestoCollection('Land', $this->context, $this->admin, array('autoload' => true));
        $collection->removeFromStore();
    }

    /*
     * From api : remove license
     */
    public function testRemoveLicense() {
        $this->initContext();
        $file_license = file_get_contents(dirname(__FILE__) . "/../data/license_Example.json");
        $data = json_decode($file_license, true);
        $this->context->dbDriver->remove(RestoDatabaseDriver::LICENSE, array('licenseId' => $data['licenseId']));
    }

    /*
     * Clear database
     */
    public function testClearDatabase() {

        $this->initContext();
        /*
         * Remove signature : testRegisteredUser
         */
        $this->context->dbDriver->fetch($this->context->dbDriver->query('DELETE from usermanagement.signatures WHERE email=\'test_email\''));
        /*
         * Clean orders
         */
        $this->context->dbDriver->fetch($this->context->dbDriver->query('DELETE from usermanagement.orders'));
        /*
         * Clean signatures
         */
        $this->context->dbDriver->fetch($this->context->dbDriver->query('DELETE from usermanagement.signatures'));
    }

}
