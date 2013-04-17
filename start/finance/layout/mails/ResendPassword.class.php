<?php

namespace start\finance\layout\mails;

class ResendPassword extends \helper\layout\LayoutBlock{
	
	private $user;
	
	public $subject = 'Nulstilling af password';
	
	function __construct(\model\finance\platform\User $user){
		$this->user = $user;
	}
	
	public function generate(){
		$link = \config\config::$configs['finance']['settings']['protocol'].'://'.\config\config::$configs['finance']['domains']['web'];
		$codeLink = $link.'/index/reset/'.$this->user->mail.'/'.$this->user->resetPasswordKey;

		return "
<h1>Nyt password</h1>

<p>Gå ind på nedenstående side for at nulstille og vælge et nyt password:</p>

<a href=\"$codeLink\">$codeLink</a>

<p>Vi ønsker dig en god fornøjelse</p>

<p>Med venlig hilsen<br/>
<b>Finansmaskinen</b><br />
<a href=\"$link\"> www.finansmaskinen.dk</a></p>


<p style=\"font-size: 90%;\">Bemærk: Hvis du har modtaget denne meddelelse ved en fejl eller hvis du ikke ønsker
at være bruger på finansmaskinen, så skal vi bede dig om blot at slette mailen. Tak.</p>";

	}
	
}

?>
