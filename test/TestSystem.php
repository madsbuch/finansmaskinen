<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 11/12/12
 * Time: 8:18 PM
 */

require_once __DIR__ . '/simpletest/autorun.php';

class testAll extends TestSuite{

	function __construct(){
		parent::__construct();
		//collect and run everything ending on .class.php
		$this->addFile('tests/System.class.php');
	}

}