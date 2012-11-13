<?php

namespace model\core;

class Request extends \model\AbstractModel{
	/**
	 * the app that the request uses
	 */
	protected $app;
	
	/**
	 * page, or method, to execute in the app controller
	 */
	protected $page;
	
	/**
	 * requested user interface: app, rpc, rest...
	 */
	protected $ui;
	
	/**
	 * return type
	 *
	 * this is read from after the . in the request url
	 */
	protected $fileType;
	
	/**
	* array of arguments, that are applied to the function call
	*/
	protected $arguments;
	
	/**
	 * id
	 *
	 * used in rpc
	 **/
	protected $id;

	/**
	 * @var object that implements the callback interface (later on)
	 */
	protected $callback;
}
