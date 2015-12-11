<?php

/**
 * Gloabl tests
 * 
 */
class t_unit extends RestoUnitTest {

    // ...

    public function testCreateLicense() {
        $this->initContext();
        $file_license = file_get_contents(dirname(__FILE__) . "/../data/license_Example.json");
        $data = json_decode($file_license, true);
        $license = new RestoLicense($this->context, $data['licenseId'], false);
        $license->setDescription($data);
    }

    /**
     * @depends testCreateLicense
     * 
     */
    public function testCreateCollection() {
        $this->initContext();
        $data = file_get_contents(dirname(__FILE__) . "/../data/Example.json");
        $data = json_decode($data, true);
        $collection = new RestoCollection($data['name'], $this->context, $this->admin);
        $collection->loadFromJSON($data, true);

        $data = file_get_contents(dirname(__FILE__) . "/../data/Landsat.json");
        $data = json_decode($data, true);
        $collection = new RestoCollection($data['name'], $this->context, $this->admin);
        $collection->loadFromJSON($data, true);
    }

    /**
     * @depends testCreateCollection
     * @expectedException              Exception
     * @expectedExceptionCode 404
     * 
     * Test error when trying to load from store a collection wich does not exist
     * 
     */
    public function testExceptionLoadCollection() {
        $this->initContext();
        $collection = new RestoCollection('Toto', $this->context, $this->admin, array('autoload' => true));
        $collection->loadFromJSON($data, false);
    }

    /**
     * @depends testCreateCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Context must be defined
     * 
     * Test error when trying to create a collection without context
     * 
     */
    public function testExceptionCreateCollectionNoContext() {
        $this->initContext();

        $collection = new RestoCollection('TATA', null, $this->admin);
    }

    /**
     * @depends testCreateCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Collection name must be an alphanumeric string not starting with a digit
     * 
     * Test error when trying to create a collection without name
     * 
     */
    public function testExceptionCreateCollectionNoName() {
        $this->initContext();

        $collection = new RestoCollection(null, $this->context, $this->admin);
    }

    /**
     * @depends testCreateCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Property "model" and collection name differ
     * 
     * Test error when trying to load a collection with a wrong model
     * 
     */
    public function testExceptionCreateCollection() {
        $this->initContext();

        $data = file_get_contents(dirname(__FILE__) . "/../data/Example.json");
        $data = json_decode($data, true);
        $collection = new RestoCollection($data['name'], $this->context, $this->admin);
        $collection->loadFromJSON($data, false);

        $data = file_get_contents(dirname(__FILE__) . "/../data/Example_error_wrong_model_name.json");
        $data = json_decode($data, true);
        $collection->loadFromJSON($data, false);
    }

    /**
     * @depends testCreateCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Property "name" and collection name differ
     * 
     * Test collection creatation with a bad json file
     *  -> missing name attribute
     */
    public function testExceptionCreateCollection_1() {

        $this->initContext();
        $data = file_get_contents(dirname(__FILE__) . "/../data/Example_error_without_name.json");
        $data = json_decode($data, true);
        $collection = new RestoCollection('Example', $this->context, $this->admin);
        $collection->loadFromJSON($data, true);
    }

    /**
     * @depends testCreateCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Property "model" is mandatory
     * 
     * Test collection creatation with a bad json file
     *  -> missing model attribute
     */
    public function testExceptionCreateCollection_2() {

        $this->initContext();
        $data = file_get_contents(dirname(__FILE__) . "/../data/Example_error_without_model.json");
        $data = json_decode($data, true);
        $collection = new RestoCollection('Example', $this->context, $this->admin);
        $collection->loadFromJSON($data, true);
    }

    /**
     * @depends testCreateCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage English OpenSearch description is mandatory
     * 
     * Test collection creatation with a bad json file
     *  -> missing osdescription
     */
    public function testExceptionCreateCollection_3() {

        $this->initContext();
        $data = file_get_contents(dirname(__FILE__) . "/../data/Example_error_without_osdescription.json");
        $data = json_decode($data, true);
        $collection = new RestoCollection('Example', $this->context, $this->admin);
        $collection->loadFromJSON($data, true);
    }

    /**
     * @depends testCreateCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Invalid input JSON
     * 
     * Test collection creatation with a bad json file
     *  -> missing osdescription
     */
    public function testExceptionCreateCollection_4() {

        $this->initContext();
        $data = 'totto';
        $collection = new RestoCollection('Example', $this->context, $this->admin);
        $collection->loadFromJSON($data, true);
    }

    /**
     * @depends testCreateCollection
     * 
     */
    public function testInsertResource() {
        $this->initContext();
        $data = file_get_contents(dirname(__FILE__) . "/../data/Landsat.json");
        $data = json_decode($data, true);
        $collection = new RestoCollection($data['name'], $this->context, $this->admin, array('autoload' => true));
        $data = file_get_contents(dirname(__FILE__) . "/../data/LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003.xml");
        $collection->addFeature(array($data));
    }

    /**
     * @depends testInsertResource
     */
    public function testResource() {
        $this->initContext();
        $data = file_get_contents(dirname(__FILE__) . "/../data/Landsat.json");
        $data = json_decode($data, true);
        $collection = new RestoCollection($data['name'], $this->context, $this->admin, array('autoload' => true));
        $feature = new RestoFeature($this->context, $this->admin, array(
            'featureIdentifier' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734',
            'collection' => $collection
        ));
        $this->assertEquals(true, $feature->isValid());
        $license = $feature->getLicense();
        $this->assertEquals('Example', $license->licenseId);
        $json = json_decode($feature->toJSON(false));
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $json->id);
        $arr_feature = $feature->toArray();
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $arr_feature['id']);
        try {
            $feature->download();
            $this->fail('An expected exception has not been raised.');
        } catch (Exception $ex) {
            
        }
    }

    /**
     * @depends testInsertResource
     * 
     */
    public function testGetCollection() {
        $this->initContext();
        $collection = new RestoCollection('Example', $this->context, $this->admin, array('autoload' => true));
        $this->assertEquals('Example', $collection->name);
        $this->assertEquals('http:///resto/collections/Example', $collection->getUrl());
        $json = json_decode($collection->toJSON(false));
        $this->assertEquals('Example', $json->name);
        /*
         * TODO :  no check for toXML function
         */
        $xml = $collection->toXML();
    }

    /**
     * @depends testInsertResource
     * 
     */
    public function testGetCollections() {
        $this->initContext();
        $collections = new RestoCollections($this->context, $this->admin, array('autoload' => true));
        $this->assertEquals('Example', $collections->getCollection('Example')->name);
        $colls = $collections->getCollections();
        $this->assertEquals('Example', $colls['Example']->name);
        $stats = $collections->getStatistics();

        $json = json_decode($collections->toJSON(false));
        $this->assertEquals('*', $json->synthesis->name);
        /*
         * TODO :   check for toXML function
         */
        $xml = $collections->toXML();
        /*
         * TODO :  chek feature collection
         */
        $featureCollection = $collections->search();

        $data = file_get_contents(dirname(__FILE__) . "/../data/Land.json");
        $data = json_decode($data, true);
        $this->assertEquals(true, $collections->create($data));
        $collections->remove('Land');
    }

    /*
     * @depends testGetCollections
     */

    public function testFeatureCollection() {
        $this->initContext();
        $featureCollection = new RestoFeatureCollection($this->context, $this->admin, null);
        $collection = new RestoCollection('Example', $this->context, $this->admin, array('autoload' => true));
        $featureCollection = new RestoFeatureCollection($this->context, $this->admin, array($collection));
        $json = json_decode($featureCollection->toJSON(false));
        $this->assertEquals('FeatureCollection', $json->type);
        /*
         * TODO : check ATOM validity
         * Cannot be tested because of parse url php function
         * $atom = $featureCollection->toATOM();
         */
    }

    /**
     * @depends testFeatureCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Context is undefined or not valid
     * 
     * Test feature collection without context
     */
    public function testExceptionFeatureCollection() {
        $this->initContext();

        $featureCollection = new RestoFeatureCollection(null, $this->admin, array());
    }

    /**
     * @depends testGetCollection
     */
    public function testCollection_toFeatureId() {
        $this->initContext();
        $collection = new RestoCollection('Landsat', $this->context, $this->admin, array('autoload' => true));
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $collection->toFeatureId('LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003'));
    }

    /**
     * @depends testFeatureCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Order with id=TOTO does not exist
     * 
     * Test order exception
     */
    public function testExceptionOrder() {
        $this->initContext();

        $order = new RestoOrder($this->admin, $this->context, 'TOTO');
    }
    
    /**
     * @depends testFeatureCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage Context must be defined
     * 
     * Test order exception
     */
    public function testExceptionOrder_context() {
        $this->initContext();

        $order = new RestoOrder($this->admin, null, 'TOTO');
    }
    
    /**
     * @depends testFeatureCollection
     * @expectedException              Exception
     * @expectedExceptionCode 500
     * @expectedExceptionMessage User must be defined
     * 
     * Test order exception
     */
    public function testExceptionRestoOrder_user() {
        $this->initContext();

        $order = new RestoOrder(null, $this->context, 'TOTO');
    }
    
    /**
     * @depends testFeatureCollection
     * 
     * Test order exception
     */
    public function testRestoOrder() {
        $this->initContext();
        
        $order = $this->admin->placeOrder(array(array('c5dc1f32-002d-5ee9-bd4a-c690461eb734')));
        $_order = new RestoOrder($this->admin, $this->context, $order['orderId']);
        $json_decode =  json_decode($_order->toJSON(true), true);
        $this->assertEquals('success', $json_decode['status']);
        $this->assertEquals(404, $json_decode['order']['errors'][0]['ErrorCode']);
        $meta4 = $_order->toMETA4();
        
        $feature = new RestoFeature($this->context, $this->admin, array('featureIdentifier' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734'));
        $order = $this->admin->placeOrder(array($feature->toArray()));
        $_order = new RestoOrder($this->admin, $this->context, $order['orderId']);
        $json_decode =  json_decode($_order->toJSON(true), true);
        $this->assertEquals(403, $json_decode['order']['errors'][0]['ErrorCode']);
        
        $profile = array(
            'userid' => 3,
            'groups' => 'default',
            'email' => 'test_email_order',
            'password' => 'test_password',
            'username' => 'test_username',
            'givenname' => 'test_givenname',
            'lastname' => 'test_lastname',
            'country' => 'FR',
            'organization' => 'FR',
            'flags' => 'REGISTERED',
            'topics' => null,
            'validatedby' => 'admin',
            'validationdate' => 'now()',
            'activated' => 1
        );

        $user = new RestoUser($profile, $this->context);
        $order = $user->placeOrder(array($feature->toArray()));
        $_order = new RestoOrder($user, $this->context, $order['orderId']);
        $json_decode =  json_decode($_order->toJSON(true), true);
        $this->assertEquals(3002, $json_decode['order']['errors'][0]['ErrorCode']);
        
        $license = new RestoLicense($this->context, 'Example', true);
        $user->signLicense($license);
        $order = $user->placeOrder(array($feature->toArray()));
        $_order = new RestoOrder($user, $this->context, $order['orderId']);
        $json_decode =  json_decode($_order->toJSON(true), true);
        $this->assertEquals($order['orderId'], $json_decode['order']['orderId']);
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $json_decode['order']['items'][0]['id']);
        $meta4 = $_order->toMETA4();
        
        $fake_array = $feature->toArray();
        $fake_array['properties']['services']['download']['url'] = null;
        $order = $user->placeOrder(array($fake_array));
        $_order = new RestoOrder($user, $this->context, $order['orderId']);
        $json_decode =  json_decode($_order->toJSON(true), true);
        $this->assertEquals(404, $json_decode['order']['errors'][0]['ErrorCode']);
        $this->assertEquals('Item not downloadable', $json_decode['order']['errors'][0]['ErrorMessage']);
        
        $fake_array['properties'] = null;
        $order = $user->placeOrder(array($fake_array));
        $_order = new RestoOrder($user, $this->context, $order['orderId']);
        $json_decode =  json_decode($_order->toJSON(true), true);
        $this->assertEquals(404, $json_decode['order']['errors'][0]['ErrorCode']);
        $this->assertEquals('Invalid item', $json_decode['order']['errors'][0]['ErrorMessage']);
        
        
        
        /*
         * TODO : test $meta4 content
         */
    }

    /**
     * @depends testGetCollection
     * 
     */
    public function testStatsCollection() {
        $this->initContext();
        $collection = new RestoCollection('Landsat', $this->context, $this->admin, array('autoload' => true));
        //$this->assertEquals('{"collection":{"Landsat":1},"instrument":{"TM":1},"platform":{"LANDSAT5":1},"processingLevel":{"LEVEL2A":1},"productType":{"REFLECTANCE":1},"sensorMode":{"XS":1}}', json_encode($collection->getStatistics()));
        //$this->assertEquals('{"name":"Landsat","status":"public","owner":"admin","model":"RestoModel_muscate","license":{"licenseId":"Example","hasToBeSigned":"once","grantedCountries":"FR,UK,SP","grantedOrganizationCountries":"FR,UK,SP,DE","grantedFlags":"REGISTERED","viewService":"public","signatureQuota":-1,"description":{"shortName":"Sentinel 1 Data","url":"https:\/\/sentinel.esa.int\/documents\/247904\/690755\/TC_Sentinel_Data_31072014.pdf"}},"osDescription":{"fr":{"ShortName":"Landsat","LongName":"Images Landsat Niveau 2A","Description":"Images de r\u00e9flectance Landsat (Level 2A) trait\u00e9es par le <a href=\"http:\/\/www.theia-land.fr\">p\u00f4le Theia<\/a> pour l\'Agence Spatiale Fran\u00e7aise (<a href=\"http:\/\/www.cnes.fr\">CNES<\/a>). L\'atelier de production d\u00e9velopp\u00e9 par le CNES utilise le prototype de cha\u00eene de Niveau 2A, MACCS, d\u00e9velopp\u00e9 et con\u00e7u au CESBIO. Les donn\u00e9es LANDSAT 8 L1T sont librement accessibles et fournies par <a href=\"http:\/\/earthexplorer.usgs.gov\">l\'USGS<\/a>","Tags":"landsat niveau2A reflectance muscate CNES","Developper":"J\u00e9r\u00f4me Gasperi","Contact":"jerome.gasperi@cnes.fr","Query":"Images sur la France entre octobre et decembre 2013","Attribution":"CNES. Copyright 2014, All Rights Reserved"},"en":{"ShortName":"Landsat","LongName":"Landsat Level2A images","Description":"Reflectance Landsat images (Level 2A) processed by the <a href=\"http:\/\/www.theia-land.fr\">Theia Land Data Center<\/a> for the French Space Agency (<a href=\"http:\/\/www.cnes.fr\">CNES<\/a>). The processing center developped by CNES uses the MACCS prototype L2A chain developped and designed by CESBIO. LANDSAT 8 L1T Input data come from <a href=\"http:\/\/earthexplorer.usgs.gov\">USGS<\/a>, that we would like to thank for releasing freely the LANDSAT 8 datasets","Tags":"landsat level2A reflectance muscate CNES","Developper":"J\u00e9r\u00f4me Gasperi","Contact":"jerome.gasperi@cnes.fr","Query":"Images acquired in France between october and december 2013","Attribution":"CNES. Copyright 2014, All Rights Reserved"}},"statistics":{"collection":{"Landsat":1},"instrument":{"TM":1},"platform":{"LANDSAT5":1},"processingLevel":{"LEVEL2A":1},"productType":{"REFLECTANCE":1},"sensorMode":{"XS":1}}}', $collection->toJSON());
    }

    /**
     * @depends testGetCollection
     */
    public function testSearchOnCollection() {

        $this->initContext();
        $collection = new RestoCollection('Landsat', $this->context, $this->admin, array('autoload' => true));

        $this->context->query = array(
            'q' => 'summer'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $errors = $searchResult['properties']['query']['analysis']['analyze']['Errors'];
        $features = $searchResult['features'];
        $this->assertEquals(0, count($errors));
        $this->assertEquals(0, count($features));

        /*
         * Test error - request not understood
         */
        $this->context->query = array(
            'q' => 'nawak'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $errors = $searchResult['properties']['query']['analysis']['analyze']['Errors'];
        $features = $searchResult['features'];
        $this->assertEquals(1, count($errors));
        $this->assertEquals(0, count($features));

        /*
         * Test year selection
         */
        $this->context->query = array(
            'q' => '2011'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $errors = $searchResult['properties']['query']['analysis']['analyze']['Errors'];
        $features = $searchResult['features'];
        $this->assertEquals(0, count($errors));
        $this->assertEquals(1, count($features));
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $features[0]['id']);

        /*
         * Test empty results on year selection
         */
        $this->context->query = array(
            'q' => '2012'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $errors = $searchResult['properties']['query']['analysis']['analyze']['Errors'];
        $features = $searchResult['features'];
        $this->assertEquals(0, count($errors));
        $this->assertEquals(0, count($features));

        /*
         * Test model
         */
        $this->context->query = array(
            'identifier' => 'LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $features = $searchResult['features'];
        $this->assertEquals(1, count($features));
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $features[0]['id']);
        $this->assertEquals('Landsat', $features[0]['properties']['collection']);
        $this->assertEquals('Example', $features[0]['properties']['license']['licenseId']);
        $this->assertEquals('once', $features[0]['properties']['license']['hasToBeSigned']);
        $this->assertEquals('LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003', $features[0]['properties']['productIdentifier']);
        $this->assertEquals('urn:ogc:def:EOP:CNES::Landsat:', $features[0]['properties']['parentIdentifier']);
        $this->assertEquals('http:///resto/collections/Landsat/c5dc1f32-002d-5ee9-bd4a-c690461eb734/wms?map=/mount/landsat/wms/map.map&file=LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003&LAYERS=landsat&FORMAT=image%2Fpng&TRANSITIONEFFECT=resize&TRANSPARENT=true&VERSION=1.1.1&SERVICE=WMS&REQUEST=GetMap&STYLES=&SRS=EPSG%3A3857&BBOX={:bbox3857:}&WIDTH=256&HEIGHT=256', $features[0]['properties']['services']['browse']['layer']['url']);
        $this->assertEquals('http:///resto/collections/Landsat/c5dc1f32-002d-5ee9-bd4a-c690461eb734/download', $features[0]['properties']['services']['download']['url']);
        $this->assertEquals('http://spirit.cnes.fr/cgi-bin/mapserv?map=/mount/landsat/wms/map.map&file=LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003&LAYERS=landsat&FORMAT=image%2Fpng&TRANSITIONEFFECT=resize&TRANSPARENT=true&VERSION=1.1.1&SERVICE=WMS&REQUEST=GetMap&STYLES=&SRS=EPSG%3A3857&BBOX={:bbox3857:}&WIDTH=256&HEIGHT=256', $features[0]['properties']['wmsInfos']);
        $this->assertEquals('/mount/landsat/archives/LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003.tgz', $features[0]['properties']['resourceInfos']['path']);

        /*
         * Test identifier
         */
        $this->context->query = array(
            'identifier' => 'XX'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $features = $searchResult['features'];
        $this->assertEquals(0, count($features));

        $this->context->query = array(
            'identifier' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $features = $searchResult['features'];
        $this->assertEquals(1, count($features));
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $features[0]['id']);

        /*
         * Test startDate
         */
        $this->context->query = array(
            'startDate' => '2011-01-01'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $features = $searchResult['features'];
        $this->assertEquals(1, count($features));
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $features[0]['id']);

        $this->context->query = array(
            'startDate' => '2014-01-01'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $features = $searchResult['features'];
        $this->assertEquals(0, count($features));

        /*
         * Test updated
         */
        $this->context->query = array(
            'updated' => '2015-01-01'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $features = $searchResult['features'];
        $this->assertEquals(1, count($features));
        $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $features[0]['id']);

        $this->context->query = array(
            'updated' => '2016-01-01'
        );
        $searchResult = $collection->search();
        $searchResult = $searchResult->toArray();
        $features = $searchResult['features'];
        $this->assertEquals(0, count($features));
    }

    /**
     * @depends testGetCollection
     */
    public function testSearchOnCollections() {
        /*
         * TODO
         */
    }

    /**
     * @depends testGetCollection
     */
    public function testAdminUser() {

        $this->initContext();

        $this->admin->activate();
        $this->assertEquals(true, $this->admin->isAdmin());
        $this->assertEquals(true, $this->admin->hasRightsTo('create'));
        $this->assertEquals(true, $this->admin->hasRightsTo('download', array('collectionName' => 'Landsat')));
        $collection = new RestoCollection('Landsat', $this->context, $this->admin, array('autoload' => true));
        $this->assertEquals(true, $this->admin->hasRightsTo('update', array('collection' => $collection)));
        $this->assertEquals(true, $this->admin->hasRightsTo('visualize', array('collectionName' => 'Landsat')));
    }

    /**
     * @depends testGetCollection
     */
    public function testRestoUser() {
        $this->initContext();
        $this->assertEquals(true, $this->admin->setRights(array(
                    'download' => 1,
                    'update' => 1,
                    'create' => 1
                        ), 'Landsat'));

        $this->assertEquals(true, $this->admin->removeRights(array(
                    'download' => 1,
                    'update' => 1,
                    'create' => 1
                        ), 'Landsat'));

        $signatures = $this->admin->getSignatures();
        $this->assertEquals(true, empty($signatures));

        $user = new RestoUser(null, $this->context);
        $this->assertEquals(false, $user->isValidated());
        $this->assertEquals(false, $user->placeOrder(array('bidon')));
    }

    /**
     * @depends testFeatureCollection
     * @expectedException              Exception
     * @expectedExceptionCode 3005
     * 
     * Test error when  sending reset password without email
     */
    public function testExceptionRestoUser() {
        $this->initContext();

        $user = new RestoUser(null, $this->context);
        $user->sendResetPasswordLink();
    }

    /**
     * @depends testFeatureCollection
     * @expectedException              Exception
     * @expectedExceptionCode 403
     * 
     * Test error when trying to connect without valid user
     */
    public function testExceptionRestoUser_1() {
        $this->initContext();

        $user = new RestoUser(null, $this->context);
        $user->connect();
    }

    /**
     * @depends testGetCollection
     */
    public function testUnregisteredUser() {

        $this->initContext();

        $profile = array(
            'userid' => -1,
            'email' => 'unregistered',
            'groups' => 'default',
            'activated' => 0
        );

        $user = new RestoUser($profile, $this->context);

        $this->assertEquals(false, $user->isAdmin());
        $this->assertEquals(false, $user->hasRightsTo('create'));
        $this->assertEquals(true, $user->hasRightsTo('download', array('collectionName' => 'Landsat')));
        $collection = new RestoCollection('Landsat', $this->context, $this->admin, array('autoload' => true));
        $this->assertEquals(false, $user->hasRightsTo('update', array('collection' => $collection)));
        $this->assertEquals(true, $user->hasRightsTo('visualize', array('collectionName' => 'Landsat')));

        $license = new RestoLicense($this->context, 'Example', true);
        $this->assertEquals(false, $license->isApplicableToUser($user));
        /*
         * Test when user has not signed license
         */
        $this->assertEquals(true, $license->hasToBeSignedByUser($user));
    }

    /**
     * @depends testGetCollection
     */
    public function testRegisteredUser() {

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

        $this->assertEquals(false, $user->isAdmin());
        $this->assertEquals(true, $user->isValidated());

        $this->assertEquals(false, $user->hasRightsTo('create'));
        $this->assertEquals(true, $user->hasRightsTo('download', array('collectionName' => 'Landsat')));
        $this->assertEquals(false, $user->hasRightsTo('update', array('collectionName' => 'Landsat')));
        $this->assertEquals(true, $user->hasRightsTo('visualize', array('collectionName' => 'Landsat')));

        $license = new RestoLicense($this->context, 'Example', true);
        $this->assertEquals(true, $license->isApplicableToUser($user));
        /*
         * Test when user has not signed license
         */
        $this->assertEquals(true, $license->hasToBeSignedByUser($user));

        /*
         * Sign license
         */
        $user->signLicense($license);



        $signatures = $user->getSignatures();
        $this->assertEquals(false, empty($signatures));

        /*
         * Test when user has signed license
         */
        $this->assertEquals(false, $license->hasToBeSignedByUser($user));

        $this->assertEquals(false, $user->activate());
        $this->assertEquals(true, array_key_exists('token', $user->connect()));
        $this->assertEquals(true, $user->disconnect());
        $_aa = $this->admin->addGroups('toto');
        $_rr = $this->admin->removeGroups('toto');
        $this->assertEquals('success', $_aa['status']);
        $this->assertEquals('success', $_rr['status']);
    }

    /**
     * @depends testGetCollection
     */
    public function testCart() {

        $this->initContext();

        $cart = $this->admin->getCart();
        $this->assertEquals(false, $cart->add('fake'));
        $cart->add(array(
            0 => array(
                'id' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734'
            )
        ));
        $items = $cart->getItems();
        foreach ($items as $key => $value) {
            $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $items[$key]['id']);
        }
        $this->assertEquals('{"46da82bf743d08f490003533a9100f55eefc5990":{"id":"c5dc1f32-002d-5ee9-bd4a-c690461eb734"}}', $cart->toJSON(false));
        $cart->remove('46da82bf743d08f490003533a9100f55eefc5990');
        $this->assertEquals(false, $cart->remove(null));
        $this->assertEquals(false, $cart->remove('fake'));
        $this->assertEquals(0, count($cart->getItems()));

        $cart->add(array(
            0 => array(
                'id' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734'
            )
        ));
        $this->assertEquals(false, $cart->update(null, 'toto'));

        $cart->update('46da82bf743d08f490003533a9100f55eefc5990', array(
            'id' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734',
            'toto' => 'titi'
        ));
        $items = $cart->getItems();
        $this->assertEquals('titi', $items['46da82bf743d08f490003533a9100f55eefc5990']['toto']);
        $cart->clear();
        $this->assertEquals(0, count($cart->getItems()));
    }

    /**
     * @depends testCreateCollection
     * @expectedException              Exception
     * @expectedExceptionCode 1001
     * @expectedExceptionMessage Cannot update item : fake does not exist
     * 
     * Test error when trying to load a collection with a wrong model
     * 
     */
    public function testExceptionCart() {
        $this->initContext();

        $cart = $this->admin->getCart();
        $cart->update('fake', 'toto');
    }

    /**
     * @depends testGetCollection
     */
    public function testCartWithSynchronize() {

        $this->initContext();

        $cart = $this->admin->getCart();
        $cart->add(array(
            0 => array(
                'id' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734'
            )
                ), true);
        $items = $cart->getItems();
        foreach ($items as $key => $value) {
            $this->assertEquals('c5dc1f32-002d-5ee9-bd4a-c690461eb734', $items[$key]['id']);
        }
        $this->assertEquals('{"46da82bf743d08f490003533a9100f55eefc5990":{"id":"c5dc1f32-002d-5ee9-bd4a-c690461eb734"}}', $cart->toJSON(false));
        $cart->remove('46da82bf743d08f490003533a9100f55eefc5990', true);
        $this->assertEquals(0, count($cart->getItems()));

        $cart->add(array(
            0 => array(
                'id' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734'
            )
                ), true);
        $cart->update('46da82bf743d08f490003533a9100f55eefc5990', array(
            'id' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734',
            'toto' => 'titi'
                ), true);
        $items = $cart->getItems();
        $this->assertEquals('titi', $items['46da82bf743d08f490003533a9100f55eefc5990']['toto']);
        $cart->clear(true);
        $this->assertEquals(0, count($cart->getItems()));
    }

    
    
    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     * @depends testGetCollection
     */
    public function testRestoRoutePOST_api_error() {
        $this->initContext();

        $restoRouteGET = new RestoRoutePOST($this->context, $this->admin);
        $segments = array('api');
        $restoRouteGET->route($segments);
    }
    
    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     * @depends testGetCollection
     */
    public function testRestoRoutePOST_api_licenses_error() {
        $this->initContext();

        $restoRouteGET = new RestoRoutePOST($this->context, $this->admin);
        $segments = array('api', 'licenses');
        $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     * @depends testGetCollection
     */
    public function testRestoRouteGET_default() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('toto');
        $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     * @depends testGetCollection
     */
    public function testRestoRouteGET_api_error() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('api');
        $restoRouteGET->route($segments);
    }

    /**
     * @expectedException              Exception
     * @expectedExceptionCode 404
     * @depends testGetCollection
     */
    public function testRestoRouteGET_api_collections_error() {
        $this->initContext();

        $restoRouteGET = new RestoRouteGET($this->context, $this->admin);
        $segments = array('api', 'collections');
        $restoRouteGET->route($segments);
        $this->fail('An expected exception has not been raised.');
    }

}
