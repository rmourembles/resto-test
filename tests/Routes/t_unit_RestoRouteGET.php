<?php

/**
 *  Tests for RestoRouteGET class
 */
class t_unit_RestoRouteGET extends RestoUnitTest {

    // ...

    public function testRestoRouteGET_api_collections() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('api', 'collections', 'describe');
        $re = $restoRouteGET->route($segments);
        /*
         * TODO : test $re content
         */
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_collections_collection_feature_download_error() {
        $this->initContext();

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

        $restoRouteGET = new RestoRouteGET($this->context, $user);
        $segments = array('collections', 'Landsat', 'c5dc1f32-002d-5ee9-bd4a-c690461eb734', 'download');
        $re = $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_api_collections_error_2() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('api', 'collections', 'toto');
        $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_api_user_error() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('api', 'user', 'toto', 'toto');
        $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_api_user_error_2() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('api', 'user', 'toto');
        $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_collections_error() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('collections', 'toto');
        $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_collections_error_1() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('collections', 'Landsat', 'c5dc1f32-002d-5ee9-bd4a-c690461eb734', 'toto');
        $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_collections_error_2() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('collections', 'Landsat', 'toto');
        $restoRouteGET->route($segments);
    }

    public function testRestoRouteGET_collections() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('collections');
        $collections = $restoRouteGET->route($segments);
        $this->assertEquals('Example', $collections->getCollection('Example')->name);

        $segments = array('collections', 'Example');
        $collection = $restoRouteGET->route($segments);
        $this->assertEquals('Example', $collection->name);

        $segments = array('collections', 'Landsat', 'c5dc1f32-002d-5ee9-bd4a-c690461eb734');
        $feature = $restoRouteGET->route($segments);
        $json = json_decode($feature->toJSON(false));
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $json->id);
    }

    public function testRestoRouteGET_user() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('user');
        $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 400
     */
    public function testRestoRouteGET_api_user_activate_error() {
        $this->initContext();

        /*
         * Error because missing query['act'] parameter
         */
        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $this->context->query['email'] = 'admin';
        $segments = array('api', 'user', 'activate');
        $feature = $restoRouteGET->route($segments);
    }

    public function testRestoRouteGET_collections_feature_wms() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('collections', 'Landsat', 'c5dc1f32-002d-5ee9-bd4a-c690461eb734', 'wms');
        //$res = $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 400
     */
    public function testRestoRouteGET_api_user_checkToken_error() {
        $this->initContext();

        /*
         * Error because missing query['_tk'] parameter
         */
        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('api', 'user', 'checkToken');
        $feature = $restoRouteGET->route($segments);
    }

    public function testRestoRouteGET_api_user_checkToken() {
        $this->initContext();

        /*
         * Error because missing query['_tk'] parameter
         */
        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('api', 'user', 'connect');
        $res = $restoRouteGET->route($segments);
        $this->assertEquals(true, isset($res['token']));

        $segments = array('api', 'user', 'checkToken');
        $this->context->query['_tk'] = $res['token'];
        $res2 = $restoRouteGET->route($segments);
        $this->assertEquals('Valid token', $res2['message']);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 400
     */
    public function testRestoRouteGET_api_user_resetPassword() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('api', 'user', 'resetPassword');
        $feature = $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 3003
     */
    /*
      public function testRestoRouteGET_15() {
      $this->initContext();

      $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
      $this->context->query['email'] = 'admin';
      $segments = array('api', 'user', 'resetPassword');
      $feature = $restoRouteGET->route($segments);
      }
     * 
     */

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_user_error_1() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('user', 'toto', 'toto');
        $res = $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_user_groups_error() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('user', 'groups', 'toto');
        $res = $restoRouteGET->route($segments);
    }

    public function testRestoRouteGET_user_groups() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('user', 'groups');
        $res = $restoRouteGET->route($segments);
        $this->assertEquals('success', $res['status']);
    }

    public function testRestoRouteGET_user_rights() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('user', 'rights');
        $res = $restoRouteGET->route($segments);
        $this->assertEquals('success', $res['status']);
    }

    public function testRestoRouteGET_user_cart() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('user', 'cart');
        $res = $restoRouteGET->route($segments);
        /*
         * TODO : add assertion
         */
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 500
     */
    public function testRestoRouteGET_user_orders_error() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        /*
         * order with orderid toto does not exists
         */
        $segments = array('user', 'orders', 'toto');
        $res = $restoRouteGET->route($segments);
    }

    public function testRestoRouteGET_user_orders() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('user', 'orders');
        $res = $restoRouteGET->route($segments);
        $this->assertEquals('success', $res['status']);
    }

    public function testRestoRouteGET_user_signatures() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('user', 'signatures');
        $res = $restoRouteGET->route($segments);
        $this->assertEquals('success', $res['status']);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testRestoRouteGET_licenses_error() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('licenses', 'toto', 'toto');
        $res = $restoRouteGET->route($segments);
    }

    public function testRestoRouteGET_licenses() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('licenses');
        $res = $restoRouteGET->route($segments);
        $this->assertEquals(true, isset($res['licenses']));
    }
    
}
