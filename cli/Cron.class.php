<?php
/**
 * User: Mads Buch
 * Date: 3/13/13
 * Time: 11:39 PM
 */

namespace cli;

/**
 * Cron modes
 *
 * cron_fast:
 *  runs at leas every five minutes or more, depending on how many instances running the farm
 *  this is not concurrency safe
 *
 * cron_slow
 *  runs like once a day or more depending on how many instances in the farm
 *  this is not concurrencysafe
 *
 * cron_safe
 *  runs like the fast one, but only on one instance. This is concurrency safe. that is
 *  multiple crons are not ran simultaniously
 *
 * Class Cron
 * @package cli
 */
class Cron{

	private $logger;

	private $argv;

	function __construct($logger, $argv){
		$this->logger = $logger;
		$this->argv = $argv;

		if(!isset($argv[2]))
			throw new \Exception('No cron mode selected');
	}

	function execute(){
		switch($this->argv[2]){
			case 'fast':
				echo "running fast mode\n";
				$this->executeFast();
				break;
			case 'slow':
				echo "running slow mode\n";
				$this->executeSlow();
				break;
			case 'concurrencySafe':
				echo "running safe mode\n";
				$this->executeConcurrencySafe();
				break;
			default:
				throw new \Exception('N such mode');
		}
	}

	function executeFast(){
		$this->executeFunction('on_cronFast');
	}

	function executeSlow(){
		$this->executeFunction('on_cronSlow');
	}

	function executeConcurrencySafe(){
		$this->executeFunction('on_cronConcurrencySafe');
	}

	function executeFunction($function){
		//get all app-apis
		$dirs = array_filter(glob(APPDIR.'*'), 'is_dir');
		foreach($dirs as $d){
			$d = explode('/', $d);
			$app = array_pop($d);
			echo "executing: " . implode('::', array('\api\\'.$app, $function)) . "\n";
			//depressing errors as a function may not be defined
			if(method_exists('\api\\'.$app, $function))
				call_user_func(array('\api\\'.$app, $function));
		}

		$dirs = array_filter(glob(ROOT.'start/*'), 'is_dir');
		foreach($dirs as $d){
			$d = explode('/', $d);
			$app = array_pop($d);
			echo "executing: " . implode('::', array('\start\\'.$app.'\api', $function)) . "\n";
			//depressing errors as a function may not be defined
			if(method_exists('\start\\'.$app.'\api', $function))
				call_user_func(array('\start\\'.$app.'\api', $function));
		}

		//TODO run on the api's in the start folder
	}
}

?>