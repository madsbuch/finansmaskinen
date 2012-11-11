<?php
/*
	ErrorHandler
	
	This object is a singleton, so we don't polute the namespace
*/
namespace core;
class errorHandler
{
	/*********** FOR SINGLETON ***********/
	// Hold an instance of the class
	private static $instance;

	// The singleton method
	public static function getInstance() {
		if (!isset(self::$instance)){
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	// Prevent users to clone the instance
	public function __clone(){
	  trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	/********* THE CLASS ***************/
	
	public function setErrorPage($error){
		echo $error;
	}
	
	
	// A private constructor; prevents direct creation of object
	private function __construct() {
		//attach erro handlers and exeption handlers
		if(DEBUG){
			set_error_handler(array($this, "debugHandler"));
			set_exception_handler(array($this, "debugExeptionHandler"));
		}
		else{
			set_error_handler(array($this, "productionHandler"));
			set_exception_handler(array($this, "productionExeptionHandler"));
		}
	}

	/**
	 * exceptionhandler in production mode
	 *
	 * write the exception to some exception log
	 *
	 * @param $exception thrown exception
	 */
	function productionExeptionHandler($exception) {
		$log = new \model\log\core\Exception();

		$log->message = $exception->getMessage();
		$log->file = "implement";
		$log->line = "implement";

		\core\logHandler::log($log);
	}

	/**
	 * exceptionhandlerhandler for the program in debug mode
	 *
	 * @param $exception thrown exception
	 */
	function debugExeptionHandler($exception) {
		echo $exception->getMessage() . "\n\n";
		var_dump($exception->getTRace());
		$this->productionExeptionHandler($exception);
	}
	
	public function productionHandler($errno, $errstr, $errfile, $errline){
		$log = new \model\log\core\Error();
		$log->errno = $errno;
		$log->errstr = $errstr;
		$log->errfile = $errfile;
		$log->errline = $errline;

		\core\logHandler::log($log);
	}
	
	public function debugHandler($errno, $errstr, $errfile, $errline){

		$this->productionHandler($errno, $errstr, $errfile, $errline);

		switch ($errno) {
			case E_USER_ERROR:
			case E_ERROR:
				\core\logHandler::logError($errstr, $errfile, $errline);
				echo "<br /><b>ERROR</b> [$errno] $errstr<br />\n";
				echo "  Fatal error on line $errline in file $errfile";
				echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			break;

			case E_USER_WARNING:
			case E_WARNING:
				echo "<br /><b>WARNING</b> [$errno] $errstr<br />\n";
				echo "  Warning on line $errline in file $errfile";
				echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
			break;

			case E_USER_NOTICE:
			case E_NOTICE:
				echo "<br /><b>NOTICE</b> [$errno] $errstr<br />\n";
				echo "Notice on line $errline in file $errfile<br />";
			break;

			default:
				echo "<br /><b>NOTICE</b> [$errno] $errstr<br />\n";
				echo "unknown error on line $errline in file $errfile<br />";
			break;
		}
	}

}

?>
