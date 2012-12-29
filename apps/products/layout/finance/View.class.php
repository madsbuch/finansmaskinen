<?php

namespace app\products\layout\finance;

use \helper\local as l;

class View extends \helper\layout\LayoutBlock{
	
	private $product;
	private $widgets;
	
	public function __construct($product, $widgets=array()){
		$this->product = $product;
		$this->widgets = $widgets;
	}
	
	function generate(){
		$dom = new \DOMDocument();
		
		//create root element of this block
		$root = $dom->createElement('div');
		$root->setAttribute('class', 'row');
		
		//create the left content
		$left = $dom->createElement('div');
		$left->setAttribute('class', 'span3');
		$root->appendChild($left);
		
		//and the right
		$right = $dom->createElement('div');
		$right->setAttribute('class', 'span9');
		$root->appendChild($right);
		
		//populating the left side
		$info = new \helper\layout\Table(array(
			'key' => 'something',
			'val' => 'theValue'
		));
		$info->showHeader = false;
		
		$info->addObject(new \model\Base(array('key' => 'Navn',
			'val' => (isset(
				$this->product->Item->Name) ? 
				$this->product->Item->Name : '-'))));

        $info->addObject(new \model\Base(array('key' => 'ID',
            'val' => (isset(
            $this->product->productID) ?
                $this->product->productID : '-'))));
		
		$info->addObject(new \model\Base(array('key' => 'Beskrivelse',
			'val' => (isset(
				$this->product->Item->Description) ? 
				$this->product->Item->Description : '-'))));
		
		$info->addObject(new \model\Base(array('key' => 'Pris',
			'val' => (isset(
				$this->product->Price->PriceAmount->_content) ? 
				l::writeValuta($this->product->Price->PriceAmount->_content, $this->product->Price->PriceAmount->currencyID, true) : '-'))));
		
			
		$left->appendChild(\helper\html::importNode($dom, $info->generate()));
		
		$left->appendChild(\helper\html::importNode($dom, 
			'<a href="/products/edit/'.$this->product->_id.'" class="btn btn-info">Rediger</a>'));
		
		$left->appendChild(\helper\html::importNode($dom, 
			' <a href="/products/catagory/'.$this->product->catagoryID
				.'" class="btn btn-info">Regnskabsinformation</a>'));
		
		//populating the right side
		foreach($this->widgets as $w){
			$widget = $dom->createElement('div');
			$widget->setAttribute('class', 'well');
			$w->wrap($widget, $dom);
			$widget = $w->generate();
			$right->appendChild($widget);
		}
		
		return $root;
	}
	
}

?>
