<?php

namespace start\finance\layout;

class MailWelcome extends \helper\layout\LayoutBlock{
	
	private $user;
	
	public $subject = 'Velkommen til finansmaskinen';
	
	function __construct(\model\finance\platform\User $user){
		$this->user = $user;
	}
	
	public function generate(){
		return '<h1>Velkommen '.$this->user->name.'</h1>
		<p>Din bruger på Finansmaskinen er blevet oprettet</p>
		<p>Klik på nedenstående link for at aktivere din konto.<br />
		Herefter vil du blive sendt igennem en lille opsætningsguide, og så er du kørende</p>
		<a href="'.\config\config::$configs['finance']['settings']['protocol'].'://'.\config\config::$configs['finance']['domains']['web'].'/index/activate/'.$this->user->activationKey.'/'.$this->user->_id.'" style="">Aktiver</a>';
	}
	
}

?>
