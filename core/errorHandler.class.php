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

	/**
	 * @var \core\framework\Output
	 */
	private $app;

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

	/**
	 * set app object to invoke when an exception has occured.
	 * this makes it possible to sent valid info to th user (ies, nice html, valid rpd response ect.)
	 *
	 * @param $app \core\app
	 */
	public function setOutput(\core\framework\Output $app){
		$this->app = $app;
	}
	
	// A private constructor; prevents direct creation of object
	private function __construct() {
		//attach erro handlers and exeption handlers
		set_error_handler(array($this, "errorHandler"));
		set_exception_handler(array($this, "exeptionHandler"));

	}

	/**
	 * exceptionhandler in production mode
	 *
	 * write the exception to some exception log
	 *
	 * @param $exception thrown exception
	 */
	function exeptionHandler(\Exception $exception) {
		try{
			$log = new \model\log\core\Exception();
			$log->message = $exception->getMessage();
			$log->stack = $exception->getTraceAsString();
			\core\logHandler::log($log);
			if(is_object($this->app)){
				$this->app->handleException($exception);
				\core\appHandler::doOutput($this->app);
			}
		}
		catch(\Exception $e){
			echo "something serious happened.\n\n";
			if(DEBUG){
				echo $e->getMessage() . "\n\n";
				echo $e->getTraceAsString() . "\n\n";
				var_dump($e->getTrace());
			}
		}
		if(DEBUG){
			echo $exception->getMessage() . "\n\n";
            echo $exception->getTraceAsString() . "\n\n";
			var_dump($exception->getTrace());
		}
	}

	/**
	 * function that transforms php errors to exceptions
	 *
	 * if in production, warnings and notices are ignored.
	 *
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 * @throws \ErrorException
	 */
	public function errorHandler($errno, $errstr, $errfile, $errline ){

		//if we do production, and the error is a NOTICE or a WARNING, proceed
		if(!DEBUG && ($errno & E_USER_NOTICE
			||  $errno & E_USER_WARNING
			||  $errno & E_WARNING
			||  $errno & E_NOTICE))
			return;

		throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
	}

}

?>
