<?php
/**
 * User: Mads Buch
 * Date: 2/10/13
 * Time: 1:30 PM
 */

namespace app\accounting\layout\finance;

class EditVatCode extends \helper\layout\LayoutBlock
{

	private $obj;

	function __construct($obj){
		$this->obj = $obj;
	}

	function generate()
	{
		return '
		<form>
			<div class="row">
				<div class="span4">
				    <label> Navn:
				        <input type="text" name="name" id="name" />
				    </label>
				</div>
			</div>
		</form>
		';
	}
}
