<?php

class t_unit_API extends PHPUnit_Framework_TestCase {

    // ...

    public function testCollectionsDescribe() {

        /*
         * Init mocked objects
         */
        // Create a stub for RestoContext class
        //$context = $this->getMockBuilder('RestoContext')->getMock();

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);

        $context->outputFormat = 'json';
        $context->path = "api/collections/describe";
        $this->assertEquals('api/collections/describe', $context->path);

        $route = new RestoRouteGET($context, $user);

        $responseObject = $route->route(explode('/', $context->path));
        $result = $responseObject->toJSON(false);

        $this->assertEquals('{"collections":[],"statistics":{"collection":{"Landsat":1},"instrument":{"TM":1},"platform":{"LANDSAT5":1},"processingLevel":{"LEVEL2A":1},"productType":{"REFLECTANCE":1},"sensorMode":{"XS":1}}}', $result);
    }

    public function testGetCollections() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);

        $context->outputFormat = 'json';
        $context->path = "collections";
        $context->method = "GET";

        $resto = new Resto($config);

        $resto->context = $context;
        $resto->user = $user;

        $response = $resto->getResponse();

        /*
         * Expected response
         */
        $_ref = '{"collections":[{"name":"Landsat","status":"public","owner":"admin","model":"RestoModel_muscate","license":null,"osDescription":{"en":{"ShortName":"Landsat","LongName":"Landsat Level2A images","Description":"Reflectance Landsat images (Level 2A) processed by the <a href=\"http:\/\/www.theia-land.fr\">Theia Land Data Center<\/a> for the French Space Agency (<a href=\"http:\/\/www.cnes.fr\">CNES<\/a>). The processing center developped by CNES uses the MACCS prototype L2A chain developped and designed by CESBIO. LANDSAT 8 L1T Input data come from <a href=\"http:\/\/earthexplorer.usgs.gov\">USGS<\/a>, that we would like to thank for releasing freely the LANDSAT 8 datasets","Tags":"landsat level2A reflectance muscate CNES","Developper":"J\u00e9r\u00f4me Gasperi","Contact":"jerome.gasperi@cnes.fr","Query":"Images acquired in France between october and december 2013","Attribution":"CNES. Copyright 2014, All Rights Reserved"},"fr":{"ShortName":"Landsat","LongName":"Images Landsat Niveau 2A","Description":"Images de r\u00e9flectance Landsat (Level 2A) trait\u00e9es par le <a href=\"http:\/\/www.theia-land.fr\">p\u00f4le Theia<\/a> pour l\'Agence Spatiale Fran\u00e7aise (<a href=\"http:\/\/www.cnes.fr\">CNES<\/a>). L\'atelier de production d\u00e9velopp\u00e9 par le CNES utilise le prototype de cha\u00eene de Niveau 2A, MACCS, d\u00e9velopp\u00e9 et con\u00e7u au CESBIO. Les donn\u00e9es LANDSAT 8 L1T sont librement accessibles et fournies par <a href=\"http:\/\/earthexplorer.usgs.gov\">l\'USGS<\/a>","Tags":"landsat niveau2A reflectance muscate CNES","Developper":"J\u00e9r\u00f4me Gasperi","Contact":"jerome.gasperi@cnes.fr","Query":"Images sur la France entre octobre et decembre 2013","Attribution":"CNES. Copyright 2014, All Rights Reserved"}},"statistics":[]}],"statistics":{"collection":{"Landsat":1},"instrument":{"TM":1},"platform":{"LANDSAT5":1},"processingLevel":{"LEVEL2A":1},"productType":{"REFLECTANCE":1},"sensorMode":{"XS":1}}}';

        $this->assertEquals($_ref, $response);
    }

    /*
     * Test Get User
     * 
     * GET user
     */

    public function testGetUser() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);

        $context->outputFormat = 'json';
        $context->path = "user";
        $context->method = "GET";

        $resto = new Resto($config);

        $resto->context = $context;
        $resto->user = $user;

        $response = $resto->getResponse();

        /*
         * Expected response
         */
        $_ref = '{"status":"success","message":"Profile for admin","profile":{"userid":"1","email":"admin","groups":"admin","activated":1}}';

        $this->assertEquals($_ref, $response);
    }

    /*
     * Test Get user groups
     * 
     * GET user/groups
     */

    public function testGetUserGroups() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);

        $context->outputFormat = 'json';
        $context->path = "user/groups";
        $context->method = "GET";

        $resto = new Resto($config);

        $resto->context = $context;
        $resto->user = $user;

        $response = $resto->getResponse();

        /*
         * Expected response
         */
        $_ref = '{"status":"success","message":"Groups for admin","email":"admin","groups":"admin"}';

        $this->assertEquals($_ref, $response);
    }

    /*
     * Test Get user rights
     * 
     * GET user/rights
     */

    public function testGetUserRights() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);

        $context->outputFormat = 'json';
        $context->path = "user/rights";
        $context->method = "GET";

        $resto = new Resto($config);

        $resto->context = $context;
        $resto->user = $user;

        $response = $resto->getResponse();

        /*
         * Expected response
         */
        $_ref = '{"status":"success","message":"Rights for admin","email":"admin","userid":"1","groups":"admin","rights":{"collections":{"*":{"download":1,"visualize":1,"create":1}},"features":[]}}';

        $this->assertEquals($_ref, $response);
    }

    /*
     * Test Get user rights on a collection
     * 
     * GET user/rights/{collection}
     */

    public function testGetUserRightsOnCollection() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);

        $context->outputFormat = 'json';
        $context->path = "user/rights/Landsat";
        $context->method = "GET";

        $resto = new Resto($config);

        $resto->context = $context;
        $resto->user = $user;

        $response = $resto->getResponse();

        /*
         * Expected response
         */
        $_ref = '{"status":"success","message":"Rights for admin","email":"admin","userid":"1","groups":"admin","rights":{"download":1,"visualize":1,"create":1}}';

        $this->assertEquals($_ref, $response);
    }

    /*
     * Test Get user signatures
     * 
     * GET user/rights/{collection}
     */

    public function testGetUserSignatures() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);

        $context->outputFormat = 'json';
        $context->path = "user/signatures";
        $context->method = "GET";

        $resto = new Resto($config);

        $resto->context = $context;
        $resto->user = $user;

        $response = $resto->getResponse();

        /*
         * Expected response
         */
        $_ref = '{"status":"success","message":"Signatures for admin","email":"admin","userid":"1","groups":"admin","signatures":[]}';

        $this->assertEquals($_ref, $response);
    }

    /*
     * Test search on collection
     * 
     * GET api/collections/{collection}/search
     */

    public function testCollectionSearch() {

        $config = include(dirname(__FILE__) . "/../conf/config.php");
        $context = new RestoContext($config);

        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);

        $context->outputFormat = 'json';
        $context->path = "api/collections/Landsat/search";
        $context->method = "GET";

        $resto = new Resto($config);

        $resto->context = $context;
        $resto->user = $user;

        $response = json_decode($resto->getResponse());

        /*
         * Expected response
         */
        $_ref = json_decode('{"type":"FeatureCollection","properties":{"title":"","id":"6e27abbc-458c-5064-b6c2-9162fa6a951f","totalResults":null,"startIndex":1,"itemsPerPage":1,"query":{"searchFilters":[],"analysis":{"query":""},"processingTime":0.097770929336548},"links":[{"rel":"self","type":"application\/json","title":"self","href":"\/\/?"},{"rel":"search","type":"application\/opensearchdescription+xml","title":"OpenSearch Description Document","href":"http:\/\/\/resto\/api\/collections\/Landsat\/describe.xml"}]},"features":[{"type":"Feature","id":"c5dc1f32-002d-5ee9-bd4a-c690461eb734","geometry":{"type":"Polygon","coordinates":[[[0.468992612052,44.861678372],[1.86155861412,44.8846910368],[1.88129630091,43.894525125],[0.512850508836,43.8719215958],[0.468992612052,44.861678372]]]},"properties":{"collection":"Landsat","license":null,"productIdentifier":"LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003","parentIdentifier":"urn:ogc:def:EOP:CNES::Landsat:","organisationName":null,"startDate":"2011-05-20T00:00:00Z","completionDate":"2011-05-20T00:00:00Z","productType":"REFLECTANCE","processingLevel":"LEVEL2A","platform":"LANDSAT5","instrument":"TM","resolution":30,"sensorMode":"XS","quicklook":"http:\/\/spirit.cnes.fr\/landsat\/ql\/LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003.png","thumbnail":"http:\/\/spirit.cnes.fr\/landsat\/ql\/LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003_thumb.png","updated":"2015-07-16T16:22:52Z","published":"2015-07-16T16:22:52Z","snowCover":0,"cloudCover":0,"keywords":{"c16401fc77fb38d":{"name":"Landsat","type":"collection","href":"\/\/?&lang=en&q=Landsat"},"988188952c531b3":{"name":"REFLECTANCE","type":"productType","href":"\/\/?&lang=en&q=REFLECTANCE"},"9ff2d85eb9bf912":{"name":"LEVEL2A","type":"processingLevel","href":"\/\/?&lang=en&q=LEVEL2A"},"affbe0c6116ee66":{"name":"landsat5","type":"platform","href":"\/\/?&lang=en&q=landsat5"},"5ff15175241ffc1":{"name":"tm","type":"instrument","parentHash":"affbe0c6116ee66","href":"\/\/?&lang=en&q=tm"},"3238372da7faa8b":{"name":"XS","type":"sensorMode","parentHash":"5ff15175241ffc1","href":"\/\/?&lang=en&q=XS"},"a5d7013fd87358f":{"name":"2011","type":"year","href":"\/\/?&lang=en&q=2011"},"07215cb3311a42c":{"name":"May","type":"month","parentHash":"a5d7013fd87358f","href":"\/\/?&lang=en&q=May"},"5cfcce1f5fb3c9e":{"name":"20","type":"day","parentHash":"07215cb3311a42c","href":"\/\/?&lang=en&q=20"}},"location":"France-Metropole","version":"2.0","productionDate":"2014-04-10T10:49:28Z","bands":"B10;B20;B30;B40;B50;B70","thermBands":"False;False;False;False;False;False","nb_cols":3667,"nb_rows":3667,"tileId":null,"oragnisationName":"CNES","services":{"browse":{"title":"Display full resolution product on map","layer":{"type":"WMS","url":"http:\/\/\/resto\/collections\/Landsat\/c5dc1f32-002d-5ee9-bd4a-c690461eb734\/wms?map=\/mount\/landsat\/wms\/map.map&file=LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003&LAYERS=landsat&FORMAT=image%2Fpng&TRANSITIONEFFECT=resize&TRANSPARENT=true&VERSION=1.1.1&SERVICE=WMS&REQUEST=GetMap&STYLES=&SRS=EPSG%3A3857&BBOX={:bbox3857:}&WIDTH=256&HEIGHT=256","layers":""}},"download":{"url":"http:\/\/\/resto\/collections\/Landsat\/c5dc1f32-002d-5ee9-bd4a-c690461eb734\/download","mimeType":"application\/x-gzip"}},"links":[{"rel":"alternate","type":"application\/json","title":"GeoJSON link for c5dc1f32-002d-5ee9-bd4a-c690461eb734","href":"\/\/?&lang=en"},{"rel":"alternate","type":"application\/atom+xml","title":"ATOM link for c5dc1f32-002d-5ee9-bd4a-c690461eb734","href":"\/\/?&lang=en"},{"rel":"via","type":"application\/unknown","title":"Metadata link for c5dc1f32-002d-5ee9-bd4a-c690461eb734","href":"http:\/\/spirit.cnes.fr\/landsat\/md\/LANDSAT5_TM_XS_20110520_N2A_France-MetropoleD0005H0003.xml"}]}}]}');

        $this->assertEquals($_ref->features[0]->id, $response->features[0]->id);
    }

    // ...
}
