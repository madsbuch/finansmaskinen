<?php
/**
* Helper class for making tiles
*
* tiles are the same as icons, just containing more info.
*
* depends: helper_html
*/

namespace helper\layout;

/**
* make a list
*
* tile argument:
* $tile = 
* array(
*	"title" => "tile title"
*	"callback" => callbackURL //AJAX callback
*	"link" => link //were does the tile take you?
*	["content" => array("line1", "line2", "line3")]//should be specified if no AJAX is available
*	["blockLink" => true|false] (default false)//links the whole box. No links are allowed inside
* 	["thumbnail" => link]//link to icon (array or string)
* );
*/
class Tiles extends LayoutBlock{
	
	/**
	* containing all tiles
	*/
	private $tiles;
	
	/**
	* waitingmessage for AJAX
	*/
	public $wait = "Henter notifikationer...";
	
	/**
	* elements pr row
	*/
	public $itemSpan = 3;
	
	/**
	* row span
	*/
	public $totalSpan = 12;
	
	/**
	* no elements to show error
	*/
	public $noElements = "Ingen elementer at vise.";
	
	function __construct(){
		$this->dom = new \DOMDocument();
	}
	
	/**
	* Add tile for later generation
	*/
	function addTile($tile){
		//add tile
		$this->tiles[] = $tile;
	}
	
	/**
	* generates tilewrap array, with all tiles
	*/
	function getTiles(){
		$html = new \helper\html($this->page, false);
		
		if(empty($this->tiles)){
			return $this->dom->createElement('p', $this->$noElements);
		}
		
		$container = $this->dom->createElement('div');
		$container->setAttribute('class', 'container');
		$this->dom->appendChild($container);
		
		$row = '';
		$i = 0;
		
		//iterating all the tiles, and make the html array
		foreach($this->tiles as $tile){
			if($i % ($this->totalSpan / $this->itemSpan) == 0){
				$row = $this->dom->createElement('div');
				$row->setAttribute('class', 'row');
				$container->appendChild($row);
			}
			
			$row->appendChild($this->createBlock($tile));
			
			/*
			$ret = array('tag' => 'div', 'attr' => array('class' => $class));
			
			//getting the block
			if(isset($tile['blocklink']) && $tile['blocklink']){
				$ret[] = array('tag' => "a", 'attr' => array("href" => $tile['link'], "class" => "t_blocklink"));
				$block = &$ret[0];
			}
			else
				$block = &$ret;
			
			//adding eventual thumb
			if(isset($tile['thumbnail'])){
				//if the whole box is not a link, the thumb has to be
				if(!isset($tile['blocklink']) || !$tile['blocklink']){
					$block[] = $html->href(
						$html->img(array("src" => $tile['thumbnail'], "alt" => $tile['title'], "class" => "t_tile")),
						$tile['link']
					);
				}
				else
					$block[] = $html->img(array("src" => $tile['thumbnail'], "alt" => $tile['title'], "class" => "t_tile"));
			}
			else
				$block[] = array('tag'=>'p');
			
			
			$block[] = array('tag' => "div", 'attr' => array("class" => "t_tileinfo"));
			$info = &$block[1];
			
			//adding title
			
			//if the whole box is not a link, the thumb has to be
			if(!isset($tile['blocklink']) || !$tile['blocklink'])
				$info[] = $html->href(
					$html->span($tile['title'], array("class" => "t_tileheader")),
					$tile['link']
				);
			else
				$info[] = $html->span($tile['title'], array("class" => "t_tileheader"));
			
			//set the helpertext
			if(isset($tile['content'])){
				$info[] = array('tag' => 'div', 'content' => $tile['content']);
			}
			elseif($this->page->struct['settings']['ajax']){
				$info[] = array('tag' => "ul", 'attr' => array('class' => 't_tile'),
					array('tag' => 'li', 'content' => $this->wait, 'attr' => array('class' => 't_tile')),
				);
			}
			
			$retArr[] = $ret;
			
			*/
			
			$i++;
		}
		
		return $container;
	}
	
	function generate(){
		return $this->getTiles();
	}
	
	private function createBlock($data){
		$block = $this->dom->createElement('div');
		$block->setAttribute('class', 'span'.$this->itemSpan);
		$block->appendChild($this->dom->createTextNode($data['title']));
		return $block;
	}

}

?>
