<?php
/**
* the widget shown on the frontpage
*/

namespace start\finance\layout;

class Personal extends \helper\layout\LayoutBlock {
	
	private $user;
	private $apiKeys;
	
	/**
	* the data to show on the widget, is as argument.
	*/
	function __construct($user, $apiKeys){
		$this->user = $user;
		$this->apiKeys = $apiKeys;
		//var_dump($this->user, $this->apiKeys);
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
			'val' => !is_null($t = $this->user->name) ? $t : '-')));
		$info->addObject(new \model\Base(array('key' => 'Mail',
			'val' => !is_null($t = $this->user->mail) ? $t : '-')));
		
		$left->appendChild(\helper\html::importNode($dom, $info->generate()));

		//privacy section
		$priv = $dom->createElement('div');
		$priv->setAttribute('class', 'well');
		$h2 = $dom->createElement('h2', __('Privacy settings'));
		$h2->appendChild($dom->createElement('small', __(' select third party plugins we may use')));
		$priv->appendChild($h2);

		$priv->appendChild($dom->createElement('p', 'Google analytics'));
		$priv->appendChild($dom->createElement('p', 'User voice'));


		$right->appendChild($priv);

		//api keys section
		$api = $dom->createElement('div');
		$api->setAttribute('class', 'well');
		
		$api->appendChild($dom->createElement('h2', __('Your api keys')));
		
		$keys = new \helper\layout\Table(array(
			'left' => 'something',
			'right' => 'theValue'
		));
		$keys->showHeader = false;
		$keys->setEmpty(__('You don\'t have any API keys yet'));
		$i = 1;
		if($this->apiKeys)
			foreach($this->apiKeys as $apiKey){
				$keys->addObject(new \model\Base(array('left' => $i,
					'right' => !is_null($t = $apiKey) ? $t['apiKey'] : '-')));
				$i++;	
			}
		$api->appendChild($this->importNode($keys, $dom));
		
		$addB = $dom->createElement('a', __('Create new key'));
		$addB->setAttribute('class', 'btn btn-primary');
		$addB->setAttribute('href', '/index/addAPIKey');
		$api->appendChild($addB);
		
		$right->appendChild($api);
		
		return $root;
	}
}


?>
