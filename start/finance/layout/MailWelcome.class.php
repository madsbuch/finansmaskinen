<?php

namespace start\finance\layout;

class MailWelcome extends \helper\layout\LayoutBlock{
	
	private $user;
	
	public $subject = 'Velkommen til finansmaskinen';
	
	function __construct(\model\finance\platform\User $user){
		$this->user = $user;
	}
	
	public function generate(){
		$link = \config\config::$configs['finance']['settings']['protocol'].'://'.\config\config::$configs['finance']['domains']['web'];
		$codeLink = $link.'/index/activate/'.$this->user->activationKey.'/'.$this->user->_id;
		return "
<h1>Velkommen til finansmaskinen</h1>

<h3>Du er nu oprettet som bruger på finansmaskinen.</h3>

<p>Dit brugernavn er: {$this->user->mail}</p>

<p>For at verificere din bruger vil vi bede dig om, at klikke på følgende link:</p>

<a href=\"$codeLink\">$codeLink</a>

<p>Når du har verificeret din mail vil du blive guidet hen til opsætningen af din bruger,
her skal du udfylde flere informationer omkring din virksomhed.</p>

<p>Hvis du ikke kan udfylde det hele på nuværende tidspunkt eller hvis du ønsker at ændre dit
password, brugernavn eller andet, så kan du også gøre det når du er logget ind i systemet.</p>


<p>Vi ønsker dig en god fornøjelse</p>

<p>Med venlig hilsen<br/>
<b>Finansmaskinen</b><br />
<a href=\"$link\"> www.finansmaskinen.dk</a></p>


<p style=\"font-size: 90%;\">Bemærk: Hvis du har modtaget denne meddelelse ved en fejl eller hvis du ikke ønsker
at være bruger på finansmaskinen, så skal vi bede dig om blot at slette mailen. Tak.</p>";

	}
	
}

?>
