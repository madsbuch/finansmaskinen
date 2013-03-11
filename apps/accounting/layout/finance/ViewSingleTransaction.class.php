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
	 * @var \model\finance\accounting\DaybookTransaction
	 */
	private $transaction;
	/**
	 * @param $daybookTransaction
	 */
	function __construct(\model\finance\accounting\DaybookTransaction $daybookTransaction){
		parent::__construct();
		$this->transaction = $daybookTransaction;
	}

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

		$table = new \helper\layout\Table(array(
			'key' => 'k',
			'value' => 'v'
		));

		$table->addObject(new \model\Base(array(
			'key' => __('Date'),
			'value' => $this->transaction->date
		)));

		$table->showHeader = false;
		$table->setEmpty(__('No information'));
		$left->appendChild($this->importContent($table->generate()));

		$content->appendChild($left);

		/**** create right side ****/
		$right = $this->dom->createElement('div');
		$right->appendChild($this->importContent('<h3>'.__('Postings included').'</h3>'));
		$right->setAttribute('class', 'span8');

		$table = new \helper\layout\Table(array(
			'account' => __('Account'),
			'.' => array(__('Amount'), function($o){
				return new \DOMText(($o->positive ? '' : '-') . l::writeValuta($o->amount));
			}),
		));
		$table->setEmpty(__('No postings on this transaction.'));
		$table->setIterator($this->transaction->postings);

		$right->appendChild($this->importContent($table));

		$content->appendChild($right);

		$holder->appendChild($content);

		return $holder;
	}
}
