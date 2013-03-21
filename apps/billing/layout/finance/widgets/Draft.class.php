<?php
/**
* shows a widget showing some bills
*/
namespace app\billing\layout\finance\widgets;

use  \helper\local as l;
class Draft extends \helper\layout\LayoutBlock implements \helper\layout\Widget{
	
	private $wrapper;
	private $edom;
	
	private $bill;
	
	function __construct($bill){
		$this->bill = $bill;
	}

	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}

	function generate(){
		$dom = $this->edom;
		$root = $this->wrapper;

		//header
		$h2 = $this->edom->createElement('h3', __('Draft'));
		$root->appendChild($h2);
		$root->appendChild(\helper\html::importNode($dom, '
		<div class="clearfix">
			<p>'.__('This bill is a draft. Finish is to send or download it.').'</p>
			<a href="/billing/remove/'.$this->bill->_id.'" class="btn btn-warning pull-right">'.__('Delete this draft.').'</a>
		</div>'));

		return $root;
	}
}

?>
