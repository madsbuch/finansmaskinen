<?php
namespace config;
class constants{
	/**
	* backwards combatability, see webroot/global.php
	*/
	
	/**
	* the magic all permissions
	*/
	const ALL				= -1;
	
	/**
	* objects
	*/
	const READ 				= 100;
	const WRITE 			= 101;
	const CREATE			= 102;
	
	/**
	* apps
	*/
	const EXE 				= 200;
	
	/**
	* user
	*/
	const ADDUSER 			= 300;
	const REMUSER 			= 301;
	const LISTMEMBERS 		= 302;
	const WRITEMETA 		= 303;
	const EDITGROUP 		= 304;
	const CREATECHILD 		= 305;
	const ACCESSCHILD 		= 306;
	
	/**
	* notification 
	*/
	const READNOTIFICATION	= 400;
	const ADDNOTIFICATION	= 401;

}
?>
