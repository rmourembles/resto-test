<?php

/**
 * Test class for collection actions :
 *  - create collection
 *  - delete collection
 *  - add feature to collection
 *  - remove feature from collection
 *  - create license
 *  - delete license
 * 
 * This test class uses admin user stored in database
 * 
 */
class t_unit extends RestoUnitTest {
    // ...

    /**
     * @covers RestoLicense::setDescription
     * @covers RestoLicense::__construct
     */
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
     * @covers RestoCollection::__construct
     * @covers RestoCollection::loadFromJSON
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
     * 
     * @covers RestoCollection::__construct
     * @covers RestoCollection::addFeature
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
     * 
     * @covers RestoCollection::__construct
     * @covers RestoCollection::getUrl
     * @covers RestoCollection::getStatistics
     * @covers RestoCollection::toJSON
     */
    public function testGetCollection() {
        $this->initContext();
        $profile = array(
            'userid' => 2,
            'email' => 'toto',
            'groups' => 'toto',
            'activated' => 1
        );

        $user = new RestoUser($profile, $this->context);

        $collection = new RestoCollection('Example', $this->context, $user, array('autoload' => true));
        $this->assertEquals('Example', $collection->name);
        $this->assertEquals('http:///resto/collections/Example', $collection->getUrl());


        $collection = new RestoCollection('Landsat', $this->context, $user, array('autoload' => true));
        $this->assertEquals('{"collection":{"Landsat":1},"instrument":{"TM":1},"platform":{"LANDSAT5":1},"processingLevel":{"LEVEL2A":1},"productType":{"REFLECTANCE":1},"sensorMode":{"XS":1}}', json_encode($collection->getStatistics()));
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
     * 
     * @covers RestoUser::isAdmin
     * @covers RestoUser::hasRightsTo
     */
    public function testAdminUser() {

        $this->initContext();

        $this->assertEquals(true, $this->admin->isAdmin());
        $this->assertEquals(true, $this->admin->hasRightsTo('create'));
        $this->assertEquals(true, $this->admin->hasRightsTo('download', array('collectionName' => 'Landsat')));
        $collection = new RestoCollection('Landsat', $this->context, $this->admin, array('autoload' => true));
        $this->assertEquals(true, $this->admin->hasRightsTo('update', array('collection' => $collection)));
        $this->assertEquals(true, $this->admin->hasRightsTo('visualize', array('collectionName' => 'Landsat')));
    }

    /**
     * @depends testGetCollection
     * 
     * @covers RestoUser::__construct
     * @covers RestoUser::isAdmin
     * @covers RestoUser::hasRightsTo
     * @covers RestoLicense::__construct
     * @covers RestoLicense::isApplicableToUser
     * @covers RestoLicense::hasToBeSignedByUser
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
        $this->assertEquals(false, $user->hasRightsTo('update', array('collectionName' => 'Landsat')));
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
     * 
     * @covers RestoUser::__construct
     * @covers RestoUser::isAdmin
     * @covers RestoUser::hasRightsTo
     * @covers RestoUser::isValidated
     * @covers RestoUser::signLicense
     * @covers RestoLicense::__construct
     * @covers RestoLicense::isApplicableToUser
     * @covers RestoLicense::hasToBeSignedByUser
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
        $user->signLicense(array(
            'licenseId' => 'Example',
            'signatureQuota' => 'once'
        ));
        /*
         * Test when user has signed license
         */
        $this->assertEquals(false, $license->hasToBeSignedByUser($user));
    }

    /**
     * @depends testInsertResource
     * 
     * @covers RestoCollection::__construct
     * @covers RestoFeature::__construct
     * @covers RestoFeature::removeFromStore
     */
    public function testRemoveResource() {
        $this->initContext();
        $data = file_get_contents(dirname(__FILE__) . "/../data/Landsat.json");
        $data = json_decode($data, true);
        $collection = new RestoCollection($data['name'], $this->context, $this->admin, array('autoload' => true));

        $feature = new RestoFeature($this->context, $this->admin, array(
            'featureIdentifier' => 'c5dc1f32-002d-5ee9-bd4a-c690461eb734',
            'collection' => $collection
        ));

        $feature->removeFromStore();
    }

    /**
     * @depends testRemoveResource
     * 
     * @covers RestoCollection::__construct
     * @covers RestoCollection::removeFromStore
     */
    public function testRemoveCollection() {
        $this->initContext();
        $collection = new RestoCollection('Example', $this->context, $this->admin, array('autoload' => true));
        $collection->removeFromStore();

        $collection = new RestoCollection('Landsat', $this->context, $this->admin, array('autoload' => true));
        $collection->removeFromStore();
    }

    /**
     * @covers RestoDatabaseDriver_PostgreSQL::remove
     * @covers Functions_licenses::removeLicense
     */
    public function testRemoveLicense() {
        $this->initContext();
        $file_license = file_get_contents(dirname(__FILE__) . "/../data/license_Example.json");
        $data = json_decode($file_license, true);
        $this->context->dbDriver->remove(RestoDatabaseDriver::LICENSE, array('licenseId' => $data['licenseId']));
    }
    
    
    public function testClearDatabase(){
        
        $this->initContext();  
        /*
         * Remove signature : testRegisteredUser
         */
        $this->context->dbDriver->fetch($this->context->dbDriver->query('DELETE from usermanagement.signatures WHERE email=\'test_email\''));
    }

}
