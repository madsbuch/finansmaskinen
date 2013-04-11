<?php
/**
* one of the widgets, tha can be shown on frontpage.
*
*
* this shows a nice graph with all the accounts in it.
*/

namespace app\accounting\layout\finance\widgets;

use \helper\local as l;

class Accounts extends \helper\layout\LayoutBlock implements \helper\layout\Widget {

	/**
	 * @var \DOMDocument
	 */
	private $edom;
	private $wrapper;

	public $tutorialSlides = array(
		'#accounting_widget_container' => 'Denne boks viser, hvordan dine fysiske pengebeholdninger burde værer. Er der de samme beløb på dem i virkeligheden som her? Så er dit regnskab formegentligt gjort rigtigt.'
	);
    
    /**
    * and some custom javascrip
    */
    public $addJs;
    private function setJS(){
    	$data = array();
	    $ticks = array();
	    $i = 0;
    	foreach($this->data as $account){
    		$data[] = array(
			    $account->name,
			    l::nonLocalWriteValuta($account->income - $account->outgoing));
		    $ticks[] = array($i, $account->name);
		    $i++;
    	}

	    $series = array();
	    $series[0]['data'] = $data;

	    $bar['data'] = $series;
	    $bar['ticks'] = $ticks;


	    $this->chartData = json_encode($bar);
	}
		
	private $data;
	private $frontpage;


	/**
	 * the data to show on the widget, is as argument.
	 *
	 * @param $data
	 * @param \app\accounting\layout\finance\widgets\whether|bool $frontpage whether to include links for fronpage
	 */
	function __construct($data, $frontpage=true){
		$this->data = $data;
		$this->frontpage = $frontpage;
		$this->setJS();
	}
	
	function wrap($wrapper, $dom){
		$this->wrapper = $wrapper;
		$this->edom = $dom;
	}
	
	function generate(){
		$this->setJS();
		
		$this->wrapper->setAttribute('id', 'accounting_widget_container');

		$h2 = $this->edom->createElement('h2', 'Regnskab');
		$h2->appendChild(new \DOMElement('small', ' Beholdninger'));
		$this->wrapper->appendChild($h2);

		$div = $this->edom->createElement('div');
		$div->setAttribute('style', 'height: 160px;font-size: 14px;line-height: 1.2em;');
		$div->setAttribute('data-charts_barData',$this->chartData);
		$this->wrapper->appendChild($div);

		
		
		if($this->frontpage){
			$btn = $this->edom->createElement('div');
			$btn->setAttribute('style', 'text-align:right;position:absolute;bottom:10px;right:10px;width:50%;');
			$btn->appendChild(
				$this->importNode('<a href="/accounting/vat"
					class="btn btn-primary">'.__('VAT statement').'</a>', $this->edom));
			
			$this->wrapper->appendChild(
				$this->importNode('
				<div style="position:absolute;bottom:10px;left:10px;">
					<a href="/accounting/" class="btn">Gå til Regnskab</a>
				</div>', $this->edom));
					
			$this->wrapper->appendChild($btn);
		}
		
		
		return $this->wrapper;
	}
}


?>
