<?php
/**
* commandline interface
*
* this file contains commandline interface. it is used for cron, debugging, etc.
*/

switch($argv[1]){
	case 'cron':
		include 'cli/cron.php';
		break;
}



?>
