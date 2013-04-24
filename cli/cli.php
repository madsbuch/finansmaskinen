<?php
/**
* commandline interface
*
* this file contains commandline interface. it is used for cron, debugging, etc.
*/

chdir("..");
require_once("core/initialize.class.php");
$ini = new \core\initialize();
$ini->initialize('finance');

if(!isset($argv[1]))
	die('No command given, interactive mode should be implmented here.' . "\n");

$logger = new \core\logHandler();

switch($argv[1]){
	case 'cron':
		echo "Executing cron.\n";
		$cron = new cli\Cron($logger, $argv);
		$cron->execute();
	break;
}



?>
