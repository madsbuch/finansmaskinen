<?php
/**
 * Index.php. The magic starts here
 *
 * This file provides basic inclusion and setup of the system
 */

/**
 * some really global seetings:
 */

/**
 * actually the only thing that should be changed when in prod.
 */
define('STRATEGY', 'production');

include '../'.STRATEGY.'/config.php';

//some character settings:
mb_internal_encoding("UTF-8");
ini_set('default_charset', 'UTF-8');
//-rw-r--r--
umask(644);

require_once("global.php");
chdir("..");

//configurations files
require_once("config/" . STRATEGY . "/config.php");
require_once("config/" . STRATEGY . "/router.php");
require_once("config/" . STRATEGY . "/const.php");


/**
 * setting initiation
 */
if (DEBUG)
	\core\debug::getInstance();

/**
 * Defining the autoload class
 */
function __autoload($class)
{


	//std classes
	if ($incl = realpath(ROOT . str_replace('\\', '/', $class) . '.class.php')) {
		include_once($incl);
		return;
	}
	//std interfaces
	if ($incl = realpath(ROOT . str_replace('\\', '/', $class) . '.interface.php')) {
		include_once($incl);
		return;
	}


	$path = explode("\\", $class);
	$className = end($path);

	//remove first element, if absolute ns is used	
	if ($path[0] == '')
		array_shift($path);

	//try loading class from core or core/extra
	if ($path[0] == "core") {
		$path = ROOT . 'core/' . $className . '.class.php';
		if (file_exists($path))
			require_once $path;
		else
			trigger_error("unable to load class: $class in $path", E_USER_WARNING);
	} //loading the helper
	/**
	 * @TODO if there is a file named controller in a subdir, it might course
	 * problems...
	 */
	elseif ($path[0] == "helper") {
		array_shift($path);
		if (is_string($path))
			$path = ROOT . 'helpers/' . $path;
		else
			$path = ROOT . 'helpers/' . implode('/', $path);
		//the controller
		if ($incl = realpath($path . '/controller.class.php')) {
			include_once($incl);
			return;
		} //std classes
		elseif ($incl = realpath($path . '.class.php')) {
			include_once($incl);
			return;
		} //std interfaces
		elseif ($incl = realpath($path . '.interface.php')) {
			include_once($incl);
			return;
		} else
			trigger_error("unable to load class: $class in " . implode('/', $path), E_USER_WARNING);
	} //load an app
	elseif ($path[0] == "app" || $path[0] == "rpc") {
		$prim = $path[0] == "app" ? ROOT . 'apps/' . $className . '/controller.class.php' :
			ROOT . 'apps/' . $className . '/rpc.class.php';

		array_shift($path);
		$alt = ROOT . 'apps/' . implode('/', $path) . '.class.php';
		if (file_exists($prim))
			require_once $prim;
		elseif (file_exists($alt))
			require_once $alt; else
			trigger_error("unable to load class: $class in $prim or $alt", E_USER_WARNING);
	} //load an app api class
	elseif ($path[0] == "api") {
		$path = ROOT . 'apps/' . $className . '/api.class.php';
		if (file_exists($path))
			require_once $path;
		else
			trigger_error("unable to load class: $class in $path", E_USER_WARNING);
	} //load start
	elseif ($path[0] == "start") {
		$alt = ROOT . implode('/', $path) . '.class.php';

		array_pop($path);
		$p = implode("/", $path); //we are sure that the include is in start
		$controller = ROOT . $p . '/controller.class.php';
		$api = ROOT . $p . '/api.class.php';

		if (file_exists($controller) && file_exists($api)) {
			require_once $controller;
			require_once $api;
		} elseif (file_exists($alt))
			require_once($alt); else
			trigger_error("unable to load profile API and controller. $controller , $api or alt $alt", E_USER_WARNING);
	} elseif ($path[0] == "config") {
		array_unshift($path, STRATEGY);
		$path[0] = 'config';
		$path[1] = STRATEGY;
		$file = ROOT . implode('/', $path) . '.class.php';
		require_once $file;

	} else {
		$alt = ROOT . implode('/', $path) . '.class.php';
		if (file_exists($alt))
			require_once $alt;
		elseif (DEBUG) {
			debug_print_backtrace();
			trigger_error("Tried loading nonexisting class: $class from $alt", E_USER_NOTICE);
		}
	}
	//can't debug in this function becouse of doubble __construct in singleton implementation :´(
}

function autoload_type($class)
{
	$class = explode('\\', $class);
	$type = array_shift($class);
	if ($type == 'exception')
		require_once(ROOT . 'exception/' . implode('/', $class) . '.interface.php');
}

spl_autoload_register('__autoload');
spl_autoload_register('autoload_type');
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
