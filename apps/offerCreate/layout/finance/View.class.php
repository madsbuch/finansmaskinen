<?php

namespace app\offerCreate\layout\finance;

class View extends \helper\layout\LayoutBlock{
	
	private $offer;
	
	public function __construct($offer){
		$this->offer = $offer;
	}
	
	function generate(){
		$this->dom = new \DOMDocument();
		$root = $this->dom->createElement('div');
		$root->setAttribute('class', 'row');
		
		$left = $this->dom->createElement('div');
		$left->setAttribute('class', 'span8');
		$root->appendChild($left);
		
		//add content for left side
		$h2 = $this->dom->createElement('h2', __('Description '));
		$h2->appendChild(new \DOMElement('small', __('Description of the task.')));
		$left->appendChild($h2);
		
		//the actul task here
		$left->appendChild($this->importNode('<div>'.$this->offer->description.'</div><hr />'));
		
		//the attachments here
		
		//and the bids here
		$h2 = $this->dom->createElement('h2', __('Bids '));
		$left->appendChild($h2);
		
		if(isset($offer->bids))
			foreach($bids as $bid){
			
			}
		else{
			$noBids = $this->dom->createElement('div', __('No bids on this task yet.'));
			$noBids->setAttribute('class', 'well');
			$left->appendChild($noBids);
		}
		
		return $root;
		return '
<div class="row">
	<div class="span8">
		<h2>Beskrivelse <small>Beskrivelse af opgaven der skal udføres.</small></h2>
		<div>
			<h3>Hej Revisorer og regnskabskyndige</h3>
			Jeg vil gerne...
		</div>
		<hr />
		<div>
			Vedhæftninger:
			<a href="#attachment-0" data-toggle="modal" class="btn">Statistik for regnskab</a>
			<a href="#attachment-1" data-toggle="modal" class="btn">Statistik for produkter</a>
			
			<div class="modal hide fade" id="attachment-0">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3>Statistik for regnskab</h3>
				</div>
				<div class="modal-body">
					antal posteringer: 1200<br />
					antal konti: 42
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-primary" data-dismiss="modal">Luk</a>
				</div>
			</div>
			
			<div class="modal hide fade" id="attachment-1">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h3>Produkt statistik</h3>
				</div>
				<div class="modal-body">
					antal produkter: 650<br />
					blah blah blah
				</div>
				<div class="modal-footer">
					<a href="#" class="btn btn-primary" data-dismiss="modal">Luk</a>
				</div>
			</div>
			
			
		</div>
		<hr />
		<h2>Bud <small></small></h2>
		<div class="well">
			<h3>Ole Andersen <small>09/04/2012 12:58</small></h3>
			<p>Jeg vilsdflne sg srth sergwrt rth fgh h trh tehhkmdh  pokdg</p>
			
			<div class="span2 offset4">
				<h3>DKK 400.00</h3>
			</div>
			<div>
				<a href="#" class="btn btn-primary">Accepter bud</a>
			</div>
		</div>
		
		<div class="well">
			<h3>Gudmund Hansen <small>09/04/2012 12:58</small></h3>
			<p>Jeg vilsdflne sg srth sergwrt rth fgh h trh tehhkmdh  pokdg</p>
			
			<div class="span2 offset4">
				<h3>DKK 300.00</h3>
			</div>
			<div>
				<a href="#" class="btn btn-primary">Accepter bud</a>
			</div>
		</div>
		
	</div>
	<div class="span4">
		<h2>Kommentarer</h2>
		<div>
			<h3>Hans petersen <small>09/04/2012 12:58</small></h3>
			blah blah blah blah blah blah blah blah blah blah 
			blah blah blah blah blah blah blah blah 
		</div>
		<hr />
		<div>
			<h3>Ole andersen <small>09/04/2012 12:58</small></h3>
			blah blsdf ser4ft gdfg blah blsdf ser4ft gdfg blah blsdf ser4ft gdfg blah blsdf ser4ft gdfg 
			blah blsdf ser4ft gdfg blah blsdf ser4ft gdfg blah blsdf ser4ft gdfg 
			blah blsdf ser4ft gdfg blah blsdf ser4ft gdfg 
			blah blsdf ser4ft gdfg 
		</div>
		<hr />
		<div>
			<h3>Kris <small>09/04/2012 12:58</small></h3>
			blah blah blih blih
		</div>
		<hr />
		<div class="well">
			<textarea style="width:100%;height:80px;"></textarea>
			<input type="submit" class="btn btn-primary" value="kommenter" />
		</div>
	</div>
</div>
		';
	}
}

?>
