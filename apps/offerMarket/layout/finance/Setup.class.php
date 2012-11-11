<?php

namespace app\offerMarket\layout\finance;

class Setup extends \helper\layout\LayoutBlock{
	
	function __construct($last){
	
	}
	
	function generate(){
		$ret = '
<div class="row">
	<div class="span12">
		<div class="well">

		</div>
	</div>
</div>
<span class="offset2">
	<a href="/offerMarket/setup/done" class="btn btn-primary btn-large offset2 span6">Videre</a>
</span>';
		
		return $ret;
	}
}

?>
