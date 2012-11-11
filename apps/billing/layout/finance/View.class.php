<?php

namespace app\billing\layout\finance;

class View extends \helper\layout\LayoutBlock{
	
	
	private $obj;
	private $widgets;
	
	/**
	* prefill some variables with the construcotr.
	*/
	function __construct($obj, $widgets){
		$this->obj = $obj;
		$this->widgets = $widgets;
	}
	
	function generate(){
		$dom = new \DOMDocument();
		
		//create root element of this block
		$root = $dom->createElement('div');
		$root->setAttribute('class', 'row');
		
		//create the left content
		$left = $dom->createElement('div');
		$left->setAttribute('class', 'span7');
		$root->appendChild($left);
		
		//and the right
		$right = $dom->createElement('div');
		$right->setAttribute('class', 'span5');
		$root->appendChild($right);
		
		//populating the left side
		$left->appendChild(\helper\html::importNode($dom, '<h3>Afsender</h3>'));
		
		$info = new \helper\layout\Table(array(
			'key' => 'something'
		));
		$info->showHeader = false;
		$info->addObject(new \model\Base(array('key' => 
			isset($this->obj->Invoice->AccountingSupplierParty->Party->PartyName->Name) ? 
			$this->obj->Invoice->AccountingSupplierParty->Party->PartyName->Name : 'Ingen'
			)));
		$left->appendChild(\helper\html::importNode($dom, $info->generate()));
		
		//and products
		$left->appendChild(\helper\html::importNode($dom, '<h3>Produkter</h3>'));
		$info = new \helper\layout\Table(array(
			'Item.Name' => 'iname',
			'InvoicedQuantity' => 'theValue',
			'LineExtensionAmount' => 'Amount'
		));
		$info->showHeader = false;
		//$info->setIterator($this->obj->Invoice->InvoiceLine);
		//$left->appendChild(\helper\html::importNode($dom, $info->generate()));
		
		
		$left->appendChild(\helper\html::importNode($dom, '<h3>Detaljer</h3>'));
		$info = new \helper\layout\Table(array(
			'key' => 'something',
			'val' => 'som value'
		));
		$info->showHeader = false;
		//$info->addObject(new \model\Base(array('key' => 'Betalt',
		//	'val' => $this->obj->isPayed ? 'Ja' : 'Nej')));
		//$left->appendChild(\helper\html::importNode($dom, $info->generate()));
		
		
		//populating the right side
		foreach($this->widgets as $w){
			$widget = $dom->createElement('div');
			$widget->setAttribute('class', 'well');
			$w->wrap($widget, $dom);
			$widget = $w->generate();
			$right->appendChild($widget);
		}
		
		return $root;
		
		
		
		
		$ret = '
<div class="row">
	<div class="span6">
		<h3>Modtager</h3>
		<table class="table">
			<tr>
				<td>AI Consult</td>
			</tr>
		</table>
		
		<h3>Produktlinjer</h3>
		<table class="table">
			<tr>
				<td>noget andet</td>
				<td>10 timer</td>
				<td>DKK 1500.00</td>
				<td>DKK 15000.00</td>
			</tr>
			<tr>
				<td>noget andet</td>
				<td>10 timer</td>
				<td>DKK 1500.00</td>
				<td>DKK 15000.00</td>
			</tr>
			<tr>
				<td>noget andet</td>
				<td>10 timer</td>
				<td>DKK 1500.00</td>
				<td>DKK 15000.00</td>
			</tr>
		</table>
		
		<h3>Andet</h3>
		<table class="table">
			<tr>
				<td>Betalt</td>
				<td><span class="label label-success">Ja</span></td>
			</tr>
			<tr>
				<td>Løbenummer</td>
				<td>13</td>
			</tr>
			<tr>
				<td>Valuta</td>
				<td>DKK</td>
			</tr>
		</table>
	</div>
	<div class="span6">
		<div class="well">
			<h3>Nemhandel status</h3>
			<div class="alert">Denne faktura er ikke sendt over nemhandel</div>
			<a class="btn btn-primary">Send over nemhandel</a>
		</div>
		<div class="well">
			<h3>Mail</h3>
			<div class="alert">Denne faktura er ikke sendt med mail</div>
			<div>
				<input style="width:45%;" type="text" placeholder="mail" />
				<select style="width:45%;">
					<option>Rå</option>
				</select>
			</div>
			<a class="btn btn-primary">Send</a>
		</div>
		
		<div class="well">
			<h3>Download</h3>
			<div>
				<label>Template:</label>
				<select>
					<option>Rå</option>
				</select>
			</div>
			<a class="btn btn-primary">Download</a>
		</div>
		
	</div>
</div>
		';
	}
}

?>
