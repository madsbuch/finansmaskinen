<?php
/**
* helper wrapper for php mailer
*/

namespace helper;

require_once PLUGINDIR.'class.phpmailer.php';
require_once PLUGINDIR.'class.smtp.php';

class mail extends \PHPMailer{
	function __construct(){
		//doing the thing with exceptions, they will be caught by the exception handler
		parent::__construct(true);
		
		//setting the credentials
		$p = \core\inputParser::getInstance()->getProfile();
		$config = \config\config::$configs[$p]['mail']['smtp'];
		$this->SMTPAuth   = true;
		$this->IsSMTP();
		$this->Host       = $config['server'];	// sets the SMTP server
  		$this->Port       = $config['port'];	// set the SMTP port for the GMAIL server
  		$this->Username   = $config['user'];	// SMTP account username
  		$this->Password   = $config['pass'];
  		$this->CharSet = 'UTF-8';
	}
}

?>
