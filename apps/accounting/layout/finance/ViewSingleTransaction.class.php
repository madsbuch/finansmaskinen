<?php
/**
 * User: Mads Buch
 * Date: 1/6/13
 * Time: 7:35 PM
 */

namespace app\accounting\layout\finance;
use \helper\local as l;
class ViewSingleTransaction extends \helper\layout\LayoutBlock
{
	/**
	 *
	 *
	 * @param $daybookTransaction
	 */
	function __construct($daybookTransaction){
		parent::__construct();
	}

	/**
	 *
	 */
	function generate(){
		$holder = $this->dom->createElement('div');

		$holder->appendChild($this->importContent(\helper\layout\Element::heading(__('Details'),
			__('for transaction'))));

		$content = $this->dom->createElement('div');
		$content->setAttribute('class', 'row');

		/**** create left side ****/
		$left = $this->dom->createElement('div');
		$left->appendChild($this->importContent('<h3>'.__('Transaction details').'</h3>'));
		$left->setAttribute('class', 'span4');


		$content->appendChild($left);

		/**** create right side ****/
		$right = $this->dom->createElement('div');
		$right->appendChild($this->importContent('<h3>'.__('Postings included').'</h3>'));



		$content->appendChild($right);

		$holder->appendChild($content);

		return $holder;
	}
}
