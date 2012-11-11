<?php

namespace app\companyProfile\layout\finance;

class Modules extends \helper\layout\LayoutBlock{
	
	private $modules;
	
	function __construct($modules){
		$this->modules = $modules;
	}
	
	public function generate(){
		$dom = new \DOMDocument();
		$root = $dom->createElement('div');
		$top = $dom->createElement('div');
		$top->setAttribute('class', 'row');
		$root->appendChild($top);
		
		$modules = $dom->createElement('div');
		$integrations = $dom->createElement('div');
		$modules->setAttribute('class', 'span6');
		$integrations->setAttribute('class', 'span6');
		
		//pending boxes
		$pModules = $dom->createElement('div');
		$pIntegrations = $dom->createElement('div');		
		$pModules->setAttribute('class', 'span6');
		$pIntegrations->setAttribute('class', 'span6');
		
		$h = $dom->createElement('h3', 'Moduler ');
		$h->appendChild($dom->createElement('small', 'Tilføj funktionalitet'));
		$modules->appendChild($h);
		
		$h = $dom->createElement('h3', 'Integrationer ');
		$h->appendChild($dom->createElement('small', 'Integrer med andre systemer'));
		$integrations->appendChild($h);
		
		$top->appendChild($modules);
		$top->appendChild($integrations);
		$root->appendChild($dom->createElement('h3', 'Under udvikling'));
		$top->appendChild($pModules);
		$top->appendChild($pIntegrations);
		
		foreach($this->modules as $url => $module){
			$m = new $module;
			
			$data = $m->getDescription();
			
			$e = $dom->createElement('div');
			$e->setAttribute('class', 'app-box span5');
			$e->setAttribute('style', 'height:120px;');
			
			$e->appendChild($dom->createElement('h4', $data->title));
			
			$e->appendChild($dom->createElement('p', $data->description));
			
			$a = $dom->createElement('a', __('More info'));
			$a->setAttribute('class', 'btn btn-primary pull-right');
			$a->setAttribute('href', '/companyProfile/modules/'.$url);
			$e->appendChild($a);
			
			if($data->integration)
				if($data->pending)
					$pIntegrations->appendChild($e);
				else
					$integrations->appendChild($e);
			else
				if($data->pending)
					$pModules->appendChild($e);
				else
					$modules->appendChild($e);
		}
		
		return $root;
		
	}
	
}

?>
