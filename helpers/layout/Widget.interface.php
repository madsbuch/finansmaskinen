<?php

namespace helper\layout;

interface Widget{
	/**
	* som content to wrap output in
	*/
	function wrap($wrapper, $dom);
}

?>
