<?php

class t_unit_Driver extends PHPUnit_Framework_TestCase {

    // ...

    /**
     * @covers Functions_cart::addToCart
     * @covers Functions_cart::getCartItems
     * @covers Functions_cart::removeFromCart
     * @covers Functions_cart::isInCart
     * @covers Functions_cart::clearCart
     */
    public function test_functions_cart() {

        $config = include(dirname(__FILE__) . "/../../conf/config.php");
        $context = new RestoContext($config);
        $functions_cart = new Functions_cart($context->dbDriver);

        $identifier = 'admin';
        $item_id = 'TEST_TEST';

        $functions_cart->addToCart($identifier, array(
            'id' => $item_id
        ));

        $this->assertEquals('{"b7efad79c31d99647e91a96c6a49a14f58d8ee55":{"id":"TEST_TEST"}}', json_encode($functions_cart->getCartItems($identifier)));


        $functions_cart->removeFromCart($identifier, 'b7efad79c31d99647e91a96c6a49a14f58d8ee55');
        $this->assertEquals('[]', json_encode($functions_cart->getCartItems($identifier)));

        $functions_cart->addToCart($identifier, array(
            'id' => $item_id
        ));
        $this->assertEquals(true, $functions_cart->isInCart("b7efad79c31d99647e91a96c6a49a14f58d8ee55"));
        $this->assertEquals(false, $functions_cart->isInCart("toto"));

        $functions_cart->clearCart($identifier);
        $this->assertEquals('[]', json_encode($functions_cart->getCartItems($identifier)));
    }
    
    /**
     * @covers Functions_rights::getGroups
     * @covers Functions_rights::getRightsForGroups
     * @covers Functions_rights::getRightsForUser
     * @covers Functions_rights::storeOrUpdateRights
     * @covers Functions_rights::removeRights
     */
    public function test_functions_rights(){
        $config = include(dirname(__FILE__) . "/../../conf/config.php");
        $context = new RestoContext($config);
        $functions_rights = new Functions_rights($context->dbDriver);
        
        
        $this->assertEquals('[{"groupid":"admin","childrens":null},{"groupid":"default","childrens":null}]', json_encode($functions_rights->getGroups()));
        $this->assertEquals('{"collections":{"*":{"download":1,"visualize":1,"create":1}},"features":[]}', json_encode($functions_rights->getRightsForGroups('admin')));
        
        
        
        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);
        $this->assertEquals('{"collections":{"*":{"download":1,"visualize":1,"create":1}},"features":[]}', json_encode($functions_rights->getRightsForUser($user)));
        
        $profile = array(
            'userid' => 2,
            'email' => 'toto',
            'groups' => 'default',
            'activated' => 0
        );

        $user = new RestoUser($profile, $context);
        $this->assertEquals('{"collections":{"Landsat":{"download":0,"visualize":0,"create":0}},"features":[]}', json_encode($functions_rights->getRightsForUser($user)));
        
        
        $functions_rights->storeOrUpdateRights(array(
            'visualize' => 1,
            'download' => 1,
            'create' => 1
        ),'user' ,'toto', 'collection', 'Landsat');
        
        $this->assertEquals('{"collections":{"Landsat":{"download":1,"visualize":1,"create":1}},"features":[]}', json_encode($functions_rights->getRightsForUser($user)));
        $functions_rights->removeRights('user', 'toto');
        $this->assertEquals('{"collections":{"Landsat":{"download":0,"visualize":0,"create":0}},"features":[]}', json_encode($functions_rights->getRightsForUser($user)));
        $this->assertEquals('{"collections":{"Landsat":{"download":0,"visualize":0,"create":0}},"features":[]}', json_encode($functions_rights->getRightsForGroups('default')));
        $this->assertEquals('{"collections":{"*":{"download":1,"visualize":1,"create":1}},"features":[]}', json_encode($functions_rights->getRightsForGroups('admin')));
    }

    /**
     * @covers Functions_collections::getCollectionsDescriptions
     * @covers Functions_collections::collectionExists
     * @covers Functions_collections::loadFromJSON
     * @covers Functions_collections::removeCollection
     * @covers Functions_collections::storeCollection
     */
    public function test_functions_collections() {
        $config = include(dirname(__FILE__) . "/../../conf/config.php");
        $context = new RestoContext($config);
        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);


        $functions_collections = new Functions_collections($context->dbDriver);

        $landsat_description = $functions_collections->getCollectionsDescriptions('Landsat');
        $this->assertEquals('{"Landsat":{"name":"Landsat","model":"RestoModel_muscate","status":"public","owner":"admin","propertiesMapping":{"oragnisationName":"CNES","parentIdentifier":"urn:ogc:def:EOP:CNES::Landsat:","quicklook":"http:\/\/spirit.cnes.fr\/landsat\/ql\/{:productIdentifier:}.png","thumbnail":"http:\/\/spirit.cnes.fr\/landsat\/ql\/{:productIdentifier:}_thumb.png","metadata":"http:\/\/spirit.cnes.fr\/landsat\/md\/{:productIdentifier:}.xml","resource":"\/mount\/landsat\/archives\/{:productIdentifier:}.tgz","resourceMimeType":"application\/x-gzip","wms":"http:\/\/spirit.cnes.fr\/cgi-bin\/mapserv?map=\/mount\/landsat\/wms\/map.map&file={:productIdentifier:}&LAYERS=landsat&FORMAT=image%2Fpng&TRANSITIONEFFECT=resize&TRANSPARENT=true&VERSION=1.1.1&SERVICE=WMS&REQUEST=GetMap&STYLES=&SRS=EPSG%3A3857&BBOX={:bbox3857:}&WIDTH=256&HEIGHT=256"},"license":null,"osDescription":{"en":{"ShortName":"Landsat","LongName":"Landsat Level2A images","Description":"Reflectance Landsat images (Level 2A) processed by the <a href=\"http:\/\/www.theia-land.fr\">Theia Land Data Center<\/a> for the French Space Agency (<a href=\"http:\/\/www.cnes.fr\">CNES<\/a>). The processing center developped by CNES uses the MACCS prototype L2A chain developped and designed by CESBIO. LANDSAT 8 L1T Input data come from <a href=\"http:\/\/earthexplorer.usgs.gov\">USGS<\/a>, that we would like to thank for releasing freely the LANDSAT 8 datasets","Tags":"landsat level2A reflectance muscate CNES","Developper":"J\u00e9r\u00f4me Gasperi","Contact":"jerome.gasperi@cnes.fr","Query":"Images acquired in France between october and december 2013","Attribution":"CNES. Copyright 2014, All Rights Reserved"},"fr":{"ShortName":"Landsat","LongName":"Images Landsat Niveau 2A","Description":"Images de r\u00e9flectance Landsat (Level 2A) trait\u00e9es par le <a href=\"http:\/\/www.theia-land.fr\">p\u00f4le Theia<\/a> pour l\'Agence Spatiale Fran\u00e7aise (<a href=\"http:\/\/www.cnes.fr\">CNES<\/a>). L\'atelier de production d\u00e9velopp\u00e9 par le CNES utilise le prototype de cha\u00eene de Niveau 2A, MACCS, d\u00e9velopp\u00e9 et con\u00e7u au CESBIO. Les donn\u00e9es LANDSAT 8 L1T sont librement accessibles et fournies par <a href=\"http:\/\/earthexplorer.usgs.gov\">l\'USGS<\/a>","Tags":"landsat niveau2A reflectance muscate CNES","Developper":"J\u00e9r\u00f4me Gasperi","Contact":"jerome.gasperi@cnes.fr","Query":"Images sur la France entre octobre et decembre 2013","Attribution":"CNES. Copyright 2014, All Rights Reserved"}}}}', json_encode($landsat_description));


        $this->assertEquals(true, $functions_collections->collectionExists('Landsat'));

        $collection = new RestoCollection('Test', $context, $user, array());
        $collection->loadFromJSON(
                array(
            "name" => "Test",
            "model" => "RestoModel_muscate",
            "status" => "public",
            "osDescription" => array(
                "en" => array(
                    "ShortName" => "Landsat",
                    "LongName" => "Landsat Level2A images",
                    "Description" => "test test",
                    "Tags" => "landsat level2A reflectance muscate CNES",
                    "Developper" => "J\u00e9r\u00f4me Gasperi",
                    "Contact" => "jerome.gasperi@cnes.fr",
                    "Query" => "Images acquired in France between october and december 2013",
                    "Attribution" => "CNES. Copyright 2014, All Rights Reserved"
                ),
                "fr" => array(
                    "ShortName" => "Landsat",
                    "LongName" => "Landsat Level2A images",
                    "Description" => "test test",
                    "Tags" => "landsat level2A reflectance muscate CNES",
                    "Developper" => "J\u00e9r\u00f4me Gasperi",
                    "Contact" => "jerome.gasperi@cnes.fr",
                    "Query" => "Images acquired in France between october and december 2013",
                    "Attribution" => "CNES. Copyright 2014, All Rights Reserved"
                )
            ),
            "propertiesMapping" => array()
                ), false);

        $this->assertEquals(false, $functions_collections->collectionExists('Test'));
        $functions_collections->storeCollection($collection, array(
            'visualize' => 1,
            'download' => 1,
            'create' => 1
        ));
        $this->assertEquals(true, $functions_collections->collectionExists('Test'));
        $functions_collections->removeCollection($collection);
        $this->assertEquals(false, $functions_collections->collectionExists('Test'));
    }
    
    /**
     * 
     */
    public function test_functions_licenses(){
        $config = include(dirname(__FILE__) . "/../../conf/config.php");
        $context = new RestoContext($config);
        $profile = array(
            'userid' => '1',
            'email' => 'admin',
            'groups' => 'admin',
            'activated' => 1
        );

        $user = new RestoUser($profile, $context);
        $functions_licenses = new Functions_licenses($context->dbDriver);
    }

}
