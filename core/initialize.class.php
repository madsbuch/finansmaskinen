<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 4/23/13
 * Time: 4:21 PM
 * To change this template use File | Settings | File Templates.
 */

namespace core;


class initialize {

	private $configuration = 'test';

	function __construct(){

	}

	/**
	 * @param null $profile string force profile
	 */
	public function initialize($profile = null){
		$this->includes();
		$this->setupAutoload();
		$this->setupConfiguration();

		if(!is_null($profile))
			\core\inputParser::getInstance()->setProfile($profile);

	}

	//region setup

	function setupConfiguration(){
		//some character settings:
		mb_internal_encoding("UTF-8");
		ini_set('default_charset', 'UTF-8');
		//umask
		umask(0777);
		//default timezone
		date_default_timezone_set('UTC');

	}

	private function includes(){
		require_once("config/" . $this->configuration . "/config.php");
		require_once("config/" . $this->configuration . "/router.php");
		require_once("config/" . $this->configuration . "/const.php");
		require_once(ROOT . "global.php");
	}

	private function setupAutoload(){
		spl_autoload_register(array($this, 'autoload_start'));
		spl_autoload_register(array($this, 'autoload'));
		spl_autoload_register(array($this, 'autoload_type'));
	}

	//endregion

	/**
	 * Defining the autoload function
	 */
	function autoload($class)
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
			array_shift($path);
			if (is_string($path))
				$path = ROOT . 'core/' . $path;
			else
				$path = ROOT . 'core/' . implode('/', $path);

			var_dump($path . '.class.php');

			$incl = realpath($path . '.class.php');

			if ($incl) {
				include_once($incl);
				return;
			}
			trigger_error("unable to load class: $class in $incl", E_USER_WARNING);
			return;
		} //loading the helper

		/**
		 * @TODO if there is a file named controller in a subdir, it might course
		 * problems...
		 */
		if ($path[0] == "helper") {
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
			return;
		}

		//load an app
		if ($path[0] == "app" || $path[0] == "rpc") {
			$prim = $path[0] == "app" ? ROOT . 'apps/' . $className . '/controller.class.php' :
				ROOT . 'apps/' . $className . '/rpc.class.php';

			array_shift($path);
			$alt = ROOT . 'apps/' . implode('/', $path) . '.class.php';
			if (file_exists($prim))
				require_once $prim;
			elseif (file_exists($alt))
				require_once $alt; else
				trigger_error("unable to load class: $class in $prim or $alt", E_USER_WARNING);
			return;
		}

		//load an app api class
		if ($path[0] == "api") {
			$path = ROOT . 'apps/' . $className . '/api.class.php';
			if (file_exists($path))
				require_once $path;
			else
				trigger_error("unable to load class: $class in $path", E_USER_WARNING);

			return;
		}



		if ($path[0] == "config") {
			array_unshift($path, $this->configuration);
			$path[0] = 'config';
			$path[1] = $this->configuration;
			$file = ROOT . implode('/', $path) . '.class.php';
			require_once $file;
			return;

		}

		$alt = ROOT . implode('/', $path) . '.class.php';
		if (file_exists($alt))
			require_once $alt;
		elseif (DEBUG) {
			debug_print_backtrace();
			trigger_error("Tried loading nonexisting class: $class from $alt", E_USER_NOTICE);
		}

		//can't debug in this function becouse of doubble __construct in singleton implementation :´(
	}

	/**
	 * the outoloader for the start objects
	 *
	 * structure as:
	 *
	 * start\profile\
	 *
	 * @param $class
	 */
	function autoload_start($class){
		$path = explode("\\", $class);
		if ($path[0] != "start")
			return;

		$alt = ROOT . implode('/', $path) . '.class.php';
		array_pop($path);
		$p = implode("/", $path); //we are sure that the include is in start
		$controller = ROOT . $p . '/controller.class.php';
		$api = ROOT . $p . '/api.class.php';

		if (file_exists($controller) && file_exists($api)) {
			require_once $controller;
			require_once $api;
		}
		elseif (file_exists($alt))
			require_once($alt);
		else
			trigger_error("unable to load profile API and controller. $controller , $api or alt $alt", E_USER_WARNING);
	}

	function autoload_type($class)
	{
		$class = explode('\\', $class);
		$type = array_shift($class);
		if ($type == 'exception')
			require_once(ROOT . 'exception/' . implode('/', $class) . '.interface.php');
	}
}