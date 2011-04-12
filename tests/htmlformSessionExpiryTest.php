<?php

namespace depage\htmlform;

/**
 * overriding time() function in our namespace
 **/
function time() {
    return 42;
}

class htmlformSessionExpiryTest extends \PHPUnit_Framework_TestCase {
    /**
     * see if the timestamp is written to session
     **/
    public function testSetTimestamp() {
        $form = new htmlform('formName', array('ttl' => 60));
        $this->assertEquals(42, $_SESSION['formName-data']['timestamp']);
    }
    
    /**
     * In an active session only the timestamp should be updated. The form data
     * remains untouched.
     **/
    public function testUpdateTimestamp() {
        $_SESSION['formName-data']['test'] = 'test';
        $_SESSION['formName-data']['timestamp'] = 1;
        $form = new htmlform('formName', array('ttl' => 60));

        $this->assertEquals('test', $_SESSION['formName-data']['test']);
        $this->assertEquals(42, $_SESSION['formName-data']['timestamp']);
    }

    /**
     * If the session timestamp is outdated, form data should be cleared.
     **/
    public function testClearSession() {
        $_SESSION['formName-data']['test'] = 'test';
        $_SESSION['formName-data']['timestamp'] = 1;
        $form = new htmlform('formName', array('ttl' => 30));

        $this->assertNull($_SESSION['formName-data']['test']);
        $this->assertEquals(42, $_SESSION['formName-data']['timestamp']);
    }
}