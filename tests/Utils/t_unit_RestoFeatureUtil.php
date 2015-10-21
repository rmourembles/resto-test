<?php

/**
 *  Tests for RestoFeatureUtil
 */
class t_unit_RestoFeatureUtil extends RestoUnitTest {

    // ...
    
    /*
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testException_toFeatureArray() {
        $this->initContext();
        
        RestoFeatureUtil::toFeatureArray(null);
    }

}
