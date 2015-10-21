<?php

/**
 *  Tests for RestoKeywordsUtil
 */
class t_unit_RestoKeywordsUtil extends RestoUnitTest {

    // ...

    public function test() {
        $this->initContext();
        
        $restoKeywordsUtil = new RestoKeywordsUtil();
        
        $this->assertEquals(false, $restoKeywordsUtil->isValid(null));
        $this->assertEquals(false, $restoKeywordsUtil->isValid(array('toto')));
        $this->assertEquals(true, $restoKeywordsUtil->isValid(array('name' => 'toto', 'type' => 'tata')));
        
        $this->assertEquals(false, $restoKeywordsUtil->areValids(null));
        $this->assertEquals(true, $restoKeywordsUtil->areValids(array(array('name' => 'toto', 'type' => 'tata'), array('name' => 'toto', 'type' => 'tata'))));
        $this->assertEquals(false, $restoKeywordsUtil->areValids(array(array('name' => 'toto', 'type' => 'tata'), 'toto')));
    }

}
