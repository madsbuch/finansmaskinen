<?php
/**
* the widget shown on the frontpage
*/

namespace start\finance\layout;

class Reset extends \helper\layout\LayoutBlock {
	

	function generate(){
		return '
		<form method="post">
			<label>Nyt password</label>
			<input type="password" name="p1" required="true" />

			<label>Nyt password igen</label>
			<input type="password" name="p2" required="true" />
			<br />
			<input type="submit" value="Nulstil" class="btn btn-success btn-large" />
		</form>
		';
	}
}


?>
