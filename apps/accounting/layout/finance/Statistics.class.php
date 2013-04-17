<?php

namespace app\accounting\layout\finance;

class Statistics extends \helper\layout\LayoutBlock{
	
	/**
	* some javascript includes for this layout
	*/
	public $addJsIncludes = array(
		'/js/plugins/jqplot/jquery.jqplot.min.js',
		'/js/plugins/jqplot/modules/jqplot.highlighter.min.js',
		'/js/plugins/jqplot/modules/jqplot.cursor.min.js',
		'/js/plugins/jqplot/modules/jqplot.categoryAxisRenderer.min.js'
    );

	
	function __construct($widgets){
		$this->widgets = $widgets;
		parent::__construct();
	}
	
	function generate(){
		$dom = $this->dom;
		$ret = $dom->createElement('div');
		
		$c = 0;
		
		$row = null;
		
		foreach($this->widgets as $w){
			if($c % 2 == 0){
				$row = $dom->createElement('div');
				$row->setAttribute('class', 'row');
				$ret->appendChild($row);
			}
			
			
			$widget = $dom->createElement('div');
			$widget->setAttribute('class', 'app-box span6');
			$widget->setAttribute('style', 'height:250px;position: relative;');
			
			$w->wrap($widget, $dom);
			
			$row->appendChild($this->importContent($w));
			$c++;
		}
		
		return $ret;
	}
	
}
