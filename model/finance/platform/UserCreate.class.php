<?php
/**
* class that newly created businesses are created from
*/


namespace model\finance\platform;

class UserCreate extends \model\AbstractModel{
	/**
	* some information about the user
	*/
	protected $name;
	protected $mail;
	protected $pass;
	protected $repass;
	
	//@TODO BETA this is for beta use, it is, thoug, not crucial to remove it...
	protected $beta;
}

?>
