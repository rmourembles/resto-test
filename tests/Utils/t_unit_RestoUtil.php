<?php

/**
 *  Tests for RestoUtil
 */
class t_unit_RestoUtil extends RestoUnitTest {

    // ...
    
    public function test() {
        $this->initContext();
        
        $this->assertEquals(false, RestoUtil::UUIDv5('toto', 'toto'));
        $this->assertEquals('tototototiti', RestoUtil::superImplode('toto', array('toto', 'titi', null)));
        $this->assertEquals('http://localhost/titi/toto/tata.format?huhu=tty.json', RestoUtil::updateUrlFormat('http://localhost/titi/toto/tata?huhu=tty.json', 'format'));
        $this->assertEquals('http://localhost/tata.format?huhu=tty.json', RestoUtil::updateUrlFormat('http://localhost/tata.json?huhu=tty.json', 'format'));
        $this->assertEquals('1977-01-01T00:00:00Z', RestoUtil::toISO8601('1977'));
        $this->assertEquals('1977-02-01T00:00:00Z', RestoUtil::toISO8601('1977-02'));
        $this->assertEquals('1977-02-10T00:00:00Z', RestoUtil::toISO8601('1977-02-10'));
        $this->assertEquals('1977-02-10T12:11:30Z', RestoUtil::toISO8601('1977-02-10T12:11:30Z'));
        
        $this->assertEquals(array('toto', 'titi', 'tata', 'tet', ' tifi ', 'cdd'), RestoUtil::splitString('toto"titi"tata tet" tifi "cdd'));
        
        $this->assertEquals(false, RestoUtil::isUrl(null));
        $this->assertEquals(false, RestoUtil::isUrl('tokijhfomqzcdna'));
        $this->assertEquals(true, RestoUtil::isUrl('http://tititit.com/foiherfoh'));
        
        $this->assertEquals(null, RestoUtil::sanitize(null));
        $this->assertEquals(array('tototot', 'tydsnc'), RestoUtil::sanitize(array('tototot', 'tydsnc')));
        $this->assertEquals('ogfdrhefvdjsncosd', RestoUtil::sanitize('ogfdrhefvdjsncosd'));
        
        $this->assertEquals('', RestoUtil::kvpsToQueryString('ogfdrhefvdjsncosd'));
        $this->assertEquals('?&toto=ttiti&frfr=trtr', RestoUtil::kvpsToQueryString(array('toto' => 'ttiti', 'frfr' => 'trtr')));
        $this->assertEquals('?&toto=ttiti&frfr=trtr&arr[]=titi&arr[]=toto', RestoUtil::kvpsToQueryString(array('toto' => 'ttiti', 'frfr' => 'trtr', 'arr' => array('toto', 'titi'))));
        
        $this->assertEquals(array('totot_tiiti_titi' => ''), RestoUtil::queryStringToKvps('totot tiiti titi'));
    }

}
