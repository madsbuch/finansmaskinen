<?php

namespace start\finance\layout;

class SignupFinish extends \helper\layout\LayoutBlock{
	
	private $user;
	
	function __construct($user){
		$this->user = $user;
	}
	
	public function generate(){
		if(isset($this->user->mail))
			$mail = $this->user->mail;
		else
			$mail = 'Det lykkedes slet ikke';
		return '<div class="hero-unit span6 offset3">
					<h1>Tillykke</h1>
					<p>Din bruger er oprettet</p>
					<p>Om kort tid modtager du en mail på <b>'.$mail.'</b>. Åben den og følg aktiveringslinket.</p>
		<p>Herefter vil du blive sendt igennem en lille opsætningsguide, og så er du kørende</p>
				</div>';
	}
	
}

?>
