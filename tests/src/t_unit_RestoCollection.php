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
        
        $collection = new RestoCollection('Landsat', $context, $user, array('autoload' => true));
        $this->assertEquals('http:///resto/collections/Landsat', $collection->getUrl());
        $this->assertEquals('{"collection":{"Landsat":1},"instrument":{"TM":1},"platform":{"LANDSAT5":1},"processingLevel":{"LEVEL2A":1},"productType":{"REFLECTANCE":1},"sensorMode":{"XS":1}}', json_encode($collection->getStatistics()));
        $this->assertEquals('{"name":"Landsat","status":"public","owner":"admin","model":"RestoModel_muscate","license":{"licenseId":null,"hasToBeSigned":null,"grantedCountries":null,"grantedOrganizationCountries":null,"grantedFlags":null,"viewService":null,"signatureQuota":null,"description":[]},"osDescription":{"en":{"ShortName":"Landsat","LongName":"Landsat Level2A images","Description":"Reflectance Landsat images (Level 2A) processed by the <a href=\"http:\/\/www.theia-land.fr\">Theia Land Data Center<\/a> for the French Space Agency (<a href=\"http:\/\/www.cnes.fr\">CNES<\/a>). The processing center developped by CNES uses the MACCS prototype L2A chain developped and designed by CESBIO. LANDSAT 8 L1T Input data come from <a href=\"http:\/\/earthexplorer.usgs.gov\">USGS<\/a>, that we would like to thank for releasing freely the LANDSAT 8 datasets","Tags":"landsat level2A reflectance muscate CNES","Developper":"J\u00e9r\u00f4me Gasperi","Contact":"jerome.gasperi@cnes.fr","Query":"Images acquired in France between october and december 2013","Attribution":"CNES. Copyright 2014, All Rights Reserved"},"fr":{"ShortName":"Landsat","LongName":"Images Landsat Niveau 2A","Description":"Images de r\u00e9flectance Landsat (Level 2A) trait\u00e9es par le <a href=\"http:\/\/www.theia-land.fr\">p\u00f4le Theia<\/a> pour l\'Agence Spatiale Fran\u00e7aise (<a href=\"http:\/\/www.cnes.fr\">CNES<\/a>). L\'atelier de production d\u00e9velopp\u00e9 par le CNES utilise le prototype de cha\u00eene de Niveau 2A, MACCS, d\u00e9velopp\u00e9 et con\u00e7u au CESBIO. Les donn\u00e9es LANDSAT 8 L1T sont librement accessibles et fournies par <a href=\"http:\/\/earthexplorer.usgs.gov\">l\'USGS<\/a>","Tags":"landsat niveau2A reflectance muscate CNES","Developper":"J\u00e9r\u00f4me Gasperi","Contact":"jerome.gasperi@cnes.fr","Query":"Images sur la France entre octobre et decembre 2013","Attribution":"CNES. Copyright 2014, All Rights Reserved"}},"statistics":{"collection":{"Landsat":1},"instrument":{"TM":1},"platform":{"LANDSAT5":1},"processingLevel":{"LEVEL2A":1},"productType":{"REFLECTANCE":1},"sensorMode":{"XS":1}}}', $collection->toJSON());
    }

}
