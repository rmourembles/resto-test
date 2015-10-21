<?php

/**
 *  Tests for RestoGeometryUtil
 */
class t_unit_RestoGeometryUtil extends RestoUnitTest {

    // ...

    public function test() {
        $this->initContext();
        
        $this->assertEquals(false, RestoGeometryUtil::isValidGeoJSONFeature(null));
        $this->assertEquals(false, RestoGeometryUtil::isValidGeoJSONFeature(array('type' => 'toto')));
        $this->assertEquals(false, RestoGeometryUtil::isValidGeoJSONFeature(array('type' => 'Feature', 'geometry' => 'toto')));
        $this->assertEquals(false, RestoGeometryUtil::isValidGeoJSONFeature(array('type' => 'Feature', 'geometry' => array())));
        $this->assertEquals(false, RestoGeometryUtil::isValidGeoJSONFeature(array('type' => 'Feature', 'geometry' => array(), 'properties' => 'toto')));
        $this->assertEquals(true, RestoGeometryUtil::isValidGeoJSONFeature(array('type' => 'Feature', 'geometry' => array(), 'properties' => array())));
        
        $this->assertEquals(null, RestoGeometryUtil::geoJSONGeometryToWKT(array('type' => 'toto', 'coordinates' => array())));
        $this->assertEquals('POINT()', RestoGeometryUtil::geoJSONGeometryToWKT(array('type' => 'POINT', 'coordinates' => array())));
        $this->assertEquals('MULTIPOINT()', RestoGeometryUtil::geoJSONGeometryToWKT(array('type' => 'MULTIPOINT', 'coordinates' => array())));
        $this->assertEquals('LINESTRING()', RestoGeometryUtil::geoJSONGeometryToWKT(array('type' => 'LINESTRING', 'coordinates' => array())));
        $this->assertEquals('MULTILINESTRING()', RestoGeometryUtil::geoJSONGeometryToWKT(array('type' => 'MULTILINESTRING', 'coordinates' => array())));
        $this->assertEquals('POLYGON()', RestoGeometryUtil::geoJSONGeometryToWKT(array('type' => 'POLYGON', 'coordinates' => array())));
        $this->assertEquals('MULTIPOLYGON()', RestoGeometryUtil::geoJSONGeometryToWKT(array('type' => 'MULTIPOLYGON', 'coordinates' => array())));
        
        $this->assertEquals(8.863358410694E-5, RestoGeometryUtil::radiusInDegrees(10, 10));
        
        $this->assertEquals(array(8.9831528424457E-5, 0.00017966305685804), RestoGeometryUtil::inverseMercator(array(10, 20)));
        $this->assertEquals(null, RestoGeometryUtil::inverseMercator(array(10)));
        $this->assertEquals(null, RestoGeometryUtil::inverseMercator('toto'));
        
        $this->assertEquals(null, RestoGeometryUtil::forwardMercator('toto'));
        $this->assertEquals(null, RestoGeometryUtil::forwardMercator(array(90,-90)));
        $this->assertEquals(null, RestoGeometryUtil::forwardMercator(array(90,90)));
        
        $this->assertEquals(null, RestoGeometryUtil::bboxToMercator(null));
        $this->assertEquals('1113194.9077778,1118889.9747022,1113194.9077778,1118889.9747022', RestoGeometryUtil::bboxToMercator('10,10,10,10'));
    }

}
