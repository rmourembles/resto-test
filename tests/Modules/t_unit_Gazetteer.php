<?php

/**
 *  Tests for Gazetteer module
 */
class t_unit_Gazetteer extends RestoUnitTest {

    // ...

    /**
     * Error because trying to access with POST HTTP request
     * 
     * @expectedException              Exception
     * @expectedExceptionCode 404
     */
    public function testGazetteer_run_error_1() {
        $this->initContext();
        
        $this->context->method = 'POST';
        $segments = array();
        $gazetteerModule = new Gazetteer($this->context, $this->admin);
        $gazetteerModule->run($segments);
    }

}
