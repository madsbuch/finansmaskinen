<?php

namespace helper\layout;

class Element{

	public static function primaryButton($href, $content, $addClass = ''){
		return \helper\html::link($href, $content, 'btn btn-primary '.$addClass);
	}
	
	public static function heading($title, $subtitle=''){
		$dom = new \DOMDocument();

		$title = new \DOMText(str_replace('&', '&amp;',$title));
		$subtitle = new \DOMText($subtitle);

		$main = $dom->createElement('header');
		$main->setAttribute('class', 'jumbotron subhead');
		$main->setAttribute('id', 'overview');

		$h1 = $dom->createElement('h1');
		$h1->appendChild($title);
		$main->appendChild($h1);

		$lead = $dom->createElement('p');
		$lead->appendChild($subtitle);
		$lead->setAttribute('class', 'lead');
		$main->appendChild($lead);

		return $main;
			/*'<header class="jumbotron subhead" id="overview">
				<h1>'.str_replace('&', '&#38;',$title).'</h1>
				<p class="lead">'.$subtitle.'</p>
			</header>';*/
	}
	
	/**  to be removed, just checking it is not used
	 * public static function jsInclude($url){
		//@TODO make this aware of profile, and url
		return '<script src="http://static.finansmaskinen.dev'.$url.'"></script>';
	}  */
}

?>
