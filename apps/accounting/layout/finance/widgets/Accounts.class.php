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

    
	public $tutorialSlides = array(
		'#accounting_widget_container' => 'Denne boks viser, hvordan dine fysiske pengebeholdninger burde værer. Er der de samme beløb på dem i virkeligheden som her? Så er dit regnskab formegentligt gjort rigtigt.'
	);
    
    /**
    * and some custom javascrip
    */
    public $addJs;
    private function setJS(){
    	$ticks = array();
    	$data = array();
    	foreach($this->data as $account){
    		$ticks[] = $account->name;
    		$data[] = l::nonLocalWriteValuta($account->income - $account->outgoing);
    		$label[] = l::writeValuta($account->income - $account->outgoing);
    	}
		$this->addJs = '
		   	$(document).ready(function(){
				var data = ['.implode(',', $data).'];
				var ticks = ["'.implode('","', $ticks).'"];
		
				plot1 = $.jqplot(\'accounting-stat\', [data], {
					// Only animate if we\'re not using excanvas (not in IE 7 or IE 8)..
					animate: !$.jqplot.use_excanvas,
					seriesDefaults:{
						renderer:$.jqplot.BarRenderer,
						pointLabels: {
							show: true,
							labels:[\''.implode('\',\'', $label).'\'] },
						rendererOptions: {
							varyBarColor: true
						}
					},
					axes: {
						xaxis: {
							renderer: $.jqplot.CategoryAxisRenderer,
							ticks: ticks
						}
					},
					highlighter: { show: false }
				});
			});';
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
		
		$ret = '
		<h2>Regnskab <small>Beholdninger</small></h2>
		<div id="accounting-stat" style="height:150px;width:100%;">
		</div>';

		$content = \helper\html::importNode($this->edom, $ret);
		
		$this->wrapper->appendChild($content);
		
		
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
