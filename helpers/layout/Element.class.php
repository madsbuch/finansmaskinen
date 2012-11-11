<?php

namespace helper\layout;

class Element{

	public static function primaryButton($href, $content, $addClass = ''){
		return \helper\html::link($href, $content, 'btn btn-primary '.$addClass);
	}
	
	public static function heading($title, $subtitle=''){
		//@TODO make this persistent to js injection (DOMDocument)
		return 
			'<header class="jumbotron subhead" id="overview">
				<h1>'.$title.'</h1>
				<p class="lead">'.$subtitle.'</p>
			</header>';
	}
	
	public static function jsInclude($url){
		//@TODO make this aware of profile, and url
		return '<script src="http://static.finansmaskinen.dev'.$url.'"></script>';
	}
}

?>
