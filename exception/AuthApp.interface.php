<?php

namespace type;

interface AuthApp
{

	/**
	 * returns if given action is authorized
	 */
	function authorizeAction($action);

}

?>
