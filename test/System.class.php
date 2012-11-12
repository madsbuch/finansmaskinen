<?php
/**
 * @author Mads Buch
 */

/**
 * This test set tests if the runtime system is installed properly
 */
class SystemTest extends UnitTestCase {

    /**
     * tests that the mongodriver exists
     */
    function testMongoDriver(){
        $this->assertTrue(extension_loaded("mongo"), 'Mongo isn\'t installed, did you put the line to the php.ini? (also in CLI)');
    }

	/**
	 * tests if the webkit html to pdf extension is installed
	 */
	function testWkhtmltopdf(){
		$this->assertTrue(extension_loaded("phpwkhtmltox"), 'wkhtmltopdf isn\'t installed, did you put the line to the php.ini? (also in CLI)');
	}
}

?>
