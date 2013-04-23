<?php
//initialize
chdir("..");
require_once("core/initialize.class.php");
$ini = new \core\initialize();
$ini->initialize();



/**
 * setting initiation
 */
if (DEBUG)
	\core\debug::getInstance();

/*
initiation:
*/

//configuration
\config\config::initialize();

//session
core\session::initialize();

//init some localization
core\localization::initialize();

//parsing input:
core\inputParser::getInstance();

//attach errorHandler
core\errorHandler::getInstance();

//parsing input
core\reqHandler::init();

//save any changed data to the dictionary
core\localization::cleanup();

//register som stats we use piwik
//core\logHandler::statistic(core\logHandler::HIT);
?>
