<?php

/**
 *  Tests for RestoFacetUtil
 */
class t_unit_RestoFacetUtil extends RestoUnitTest {

    // ...

    public function test() {
        $this->initContext();
        
        $restoFacetUtil = new RestoFacetUtil();
        
        $this->assertEquals(null, $restoFacetUtil->getFacetCategory(null));
        $this->assertEquals('toto', $restoFacetUtil->getFacetCategory('toto'));
        
        $this->assertEquals(null, $restoFacetUtil->getFacetParentType(null));
        $this->assertEquals('year', $restoFacetUtil->getFacetParentType('month'));
        $this->assertEquals(null, $restoFacetUtil->getFacetParentType('year'));
        
        $this->assertEquals(null, $restoFacetUtil->getFacetChildrenType(null));
        $this->assertEquals('day', $restoFacetUtil->getFacetChildrenType('month'));
        $this->assertEquals(null, $restoFacetUtil->getFacetChildrenType('day'));
    }

}
