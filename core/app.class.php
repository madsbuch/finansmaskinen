<?php
/**
* abstract class for all apps
*
* note:
* as often as possible, check, and add to the page, the content of 
* helper_core::getUsrMsg(), so the users can get their messages
*/
namespace core;
abstract class app{
	
	/**
	* request object
	*/
	protected $request;
	
	/**
	* the request parameters
	*/
	protected $param;
	
	/**
	* constructing
	*
	* setting local field variables as parameters
	*/
	function __construct($request){
		$params = \core\inputParser::getInstance()->getParameters();
		
		$this->request = $request;
		
		foreach($params as $k => $v)
			$this->param[$k] = $v;
		
	}
	
	/**** VARIABLES ****/
	
	/**
	* some caching control
	*
	* this is only very simple caching, for more advanced options, use 
	* helper\cache
	*
	* this one always caches lacally, and should mostly be used for static pages.
	* this also caches on the argument.
	*/
	public $cacheExpire = -1;
	
	/**** FUNCTIONALITY ****/
	
	/**
	* get api for primary site
	*/
	function getSiteAPI(){
		$site = \core\inputParser::getInstance()->getSite();
		$site = 'start\\'.$site.'\api';
		return new $site;
	}
	
	/**
	* call a function on all available apps (their api's)
	*
	* this function calls $functionName on all available api's.
	*
	* e.g. the contact app has the ability to show a little activity from other
	* apps, when a contact is viewed. The contact app then calls:
	*  $this->callAll('getContactActivity').
	*
	* this returns a collection (array), of values returned by the functions called
	* contact may require the return to be of a vertain type, an error will be thrown
	* if it is not the case
	*/
	function callAll($functionName, $args=null){
		$apps = $this->getSiteAPI()->appIterator();
		$ret = array();
		foreach($apps as $app){
			$callback = array('api\\'.$app->name, 'on_'.$functionName);
			if(method_exists($callback[0], $callback[1])){
				$r = null;
				if($args)
					$r = call_user_func_array($callback, $args);
				else
					$r = call_user_func($callback);
				
				//@TODO check type, if $returnType is set
				
				if(is_array($r))
					$ret = array_merge($ret, $r);
				else 
					$ret[] = $r;
			}
		}
		return $ret;
	}
	
	/**
	* set message
	*
	* this sets a message that is pushed to the user
	*/
	function setUserMsg($key, $msg){
		\core\session::getInstance()->coreUserMessage[$key] = $msg;
	}
	
	/**
	* get message
	*
	* returns array of messages, or null if none in the queue
	*/
	function getUserMsg(){
		if(isset(\core\session::getInstance()->coreUserMessage))
			return \core\session::getInstance()->coreUserMessage;
		null;
	}
	
	/**
	* clears one or more messages
	*/
	function clearUserMsg($key = null){
		if(!$key)
			\core\session::getInstance()->coreUserMessage = array();
		else
			unset(\core\session::getInstance()->coreUserMessage[$key]);
	}
	
	/**
	* notify the user
	*
	* add notification to some groups notification stack
	* @param $grp	the group to notify
	* @param $link	last part of the url, eg /contacts/view/-someid-
	* @param $link	the actual message, this is localized later on, see documentation
	*				for __() for formatting (in short, like php's sprintf)
	*/
	function notify($grp, $link, $msg){
	
	}
	
	/**** PROTOTYPES ****/
	
	/**
	* setup function
	*
	* this is called upon initialising app to user.
	* this should return true, when setup is finished, the user is maybe
	* redirected to this site untill setup completion, so eventualle this should
	* return true
	*/
	function setup(){
		return true;
	}
	
	/**
	* prototype for a errorpage
	*/
	function getErrorPage($errornum){
		return $errornum;
	}
	
	/*** ABSTRACT METHODS ****/
	
	abstract function getOutputHeader();
	abstract function getOutputContent();
	
}
