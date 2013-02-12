<?php
/**
* the widget shown on the frontpage
*/

namespace start\finance\layout;

class Main extends \helper\layout\LayoutBlock {
	
	/**
	* some javascript includes for this layout
	*/
	public $addJsIncludes = array(
		'/js/plugins/jquery.tools.min.js'
    );
    
    public $tutorialSlides = array(
		'#primaryNavTitle' => array('Her er din primære menu, har har du adgang til værktøjer der er aktiveret for denne virksomhed.', 'bottom'),
		'#naturalCommander' => array('I denne boks kan du bede rengskabsprogrammet om at udfører en opgave. F.eks. kan du skrive "Opret faktura til Ole på 5 mælk", derefter vil programmet gå ind og oprette en faktura til ole på 5 mælk, hvis du har ole some kontakt og mælk som produk.', 'bottom'),
	);
    
    /**
    * and some custom javascrip
    */
    public $addJs = '
    
    ';
	
	/**
	* the data to show on the widget, is as argument.
	*/
	private $widgets;
	
	function __construct($widgets){
		$this->widgets = $widgets;
		parent::__construct();
	}
	
	function generate(){
		$dom = $this->dom;
		$ret = $dom->createElement('div');
		$ret->setAttribute('class', 'row');
		
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
			$widget->setAttribute('style', 'height:260px;position:relative;');
			
			$w->wrap($widget, $dom);
			
			$row->appendChild($this->importContent($w));
			$c++;
		}
		
		return $ret;
	}
}


?>
