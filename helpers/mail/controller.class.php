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
		$this->IsSMTP();
        $this->SMTPAuth   = true;
		$this->Host       = $config['server'];	// SMTP server
  		$this->Port       = $config['port'];	// SMTP port
  		$this->Username   = $config['user'];	// SMTP account username
  		$this->Password   = $config['pass'];
  		$this->CharSet = 'UTF-8';
	}
}

?>
