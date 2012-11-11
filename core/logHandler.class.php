<?php
/**
* This class provides log functionality.
* 
* caching can be built in by writing all files to local cache, and then to db.
*/
namespace core;
class logHandler{


	/**
	 * takes a log object and writes it to the
	 *
	 * @param $entry a nonnested object or array to write to log
	 * @param $logName overrides eventually specified filename from object, needed if not types entry
	 */
	public static function log($entry, $logName=null){
		//parsing entry
		$file = LOGDIR;
		$level = 'INFO';
		$entry = (array) $entry;
		if(isset($entry['_filename']))
			$file .= $entry['_filename'];
		if(isset($entry['_level']))
			$level = $entry['_level'];

		unset($entry['_level'], $entry['_filename']);

		$str = date('c');
		$str .= ' ' .$level;
		$str .= ' ' . json_encode($entry);

		$str .= "\n";
		file_put_contents($file, $str, FILE_APPEND | LOCK_EX);
	}

	/**** everything from down here is deprecated ****/

	/**
	* Log types
	*
	* 1000:	Fatal error fault: to be upload as fast as possible to database server
	* 2000:	Warnings: high priority upload
	* 3000:	general logs.
	* 4000:	statistics: statistic logs: sessions_start (and information),
			every hit. Serverside generated statistics
	*/

	const FATAL_ERROR	= 1000;
	const WARNING		= 2000;
	const NOTICE		= 3000;
	const LOG			= 4000;
	const STATISTIC		= 5000;

	/**
	* types of statistic logging
	*/
	const SESSION	= "stats_sessions";//log session (sessionID, useragent, refere, entrance (arguments called)
	const HIT		= "stats_hits";//log every hit, (sessionID, IP, path, time)
	const LOGIN		= "stats_logins";//a logged ind person (sessionID, userid, time)

	/**
	* log an error
	*/
	static function logError($msg, $file, $line, $priority=1){
		$session = \core\session::getInstance();
		$arr = array(
			"msg" => $msg,
			"file" => $file,
			"line" => $line,
			"priority" => $priority,
			"sid" => $session->getSid(),
		);
		
		self::writeDB($arr, "error");
		
	}
	
	/**
	* log a warning
	*/
	static function logWarning($msg, $line, $file){
		self::logError($msg, $line, $file, 2);
	}
	
	/**
	* log a notice
	*/
	static function logNotice($msg, $line, $file){
		self::logError($msg, $line, $file, 3);
	}
	
	/**
	* normal system log (sms sent, mail sent, invoice created etc...)
	*/
	/*static function log($msg, $key){
		$session = \core\session::getInstance();
		$arr = array(
			"key" => $key,
			"msg" => $msg,
			"sid" => $session->getSid(),
		);
		
		self::writeDB($arr, "log");
	}*/
	
	/**
	* core statistics, types defined in the start.
	*/
	static function statistic($type){
		$session = session::getInstance();
		$input = inputParser::getInstance();
		$auth = auth::getInstance();
		
		switch($type){
			case self::SESSION:
				$arr = array(
					"sid" => $session->getSid(),
					"ua" => \core\client::getUA(),
					"ref" => \core\client::getRef(),
					"entrance" => $input->getURL(),
				);
			break;
			case self::HIT:
				$arr = array(
					"sid" => $session->getSid(),
					"ip" => \core\client::getIP(),
					"path" => $input->getURI(),
					"time" => \core\time::getUnixTime(),
				);
			break;
			case self::LOGIN:
				$arr = array(
					"sid" => $session->getSid(),
					"uid" => $auth->getUID(),
					"time" => \core\time::getUnixTime(),
				);
			break;
		}
		
		self::writeDB($arr, $type);
	}
	
	/**
	* flush cache to db.
	*/
	static function flushCache(){
	
	}
	
	/**
	* function to upload log entry to database.
	*/
	private static function writeDB($arr, $table){
		$db = new \core\db(\config\config::$logConfig);
		
		if(!$db->insert($arr, $table))
			self::writeFile($arr, $table);
		
		return true;
	}
	
	/**
	* if data can't be written to db, it will be written to local cache
	*/
	private static function writeFile($arr, $table){
		//var_dump($arr);
	}

}
?>
