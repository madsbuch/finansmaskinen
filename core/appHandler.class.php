<?php
namespace core;
class appHandler{
	
	static function doOutput(& $app){
		//makes sure that output is utf-8
		output::$content = $app->getOutputContent();
		output::$header = $app->getOutputHeader();
		output::send();
	}
	
}

?>
