<?php
/**
 * @author Mads Buch
 */

/**
 * This test set tests if the runtime system is installed properly
 */
class SystemTest extends UnitTestCase {

	//region Plugins
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

	function testMysql(){
		$this->assertTrue(extension_loaded('pdo_mysql'), 'PDO with MySQL isn\'t installed.');
	}
	//endregion

	//region Runtime system (configurations)

	/**
	 * tests that execution time is 5 seconds
	 *
	 * 5 seconds is choosen, as no user wanna wait more than 5 seconds on data.
	 * If API calls takes more than 5 seconds, they should be restricted (restricted number of max objects fetched e.g.)
	 *
	 * the motivation is, that if a page takes more than 5 seconds to load, something is wrong, and we'd rather
	 * close the execution, and free the resources.
	 */
	function testExecutionTime(){

	}

	/**
	 * tests for proper memory limit
	 */
	function testMemLimit(){

	}

	//endregion
}

?>
