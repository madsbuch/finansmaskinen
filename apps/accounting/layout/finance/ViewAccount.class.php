<?php
/**
 * User: Mads Buch
 * Date: 1/19/13
 * Time: 9:44 PM
 */

namespace app\accounting\layout\finance;

class ViewAccount extends \helper\layout\LayoutBlock
{
	private $account;
	private $postings;

	function __construct(\model\finance\accounting\Account $account, $postings = array()){
		parent::__construct();

		$this->postings = $postings;
		$this->account = $account;
	}


	function generate()
	{
		$holder = $this->dom->createElement('div');

		$content = $this->dom->createElement('div');
		$content->setAttribute('class', 'row');

		/**** create left side ****/
		$left = $this->dom->createElement('div');
		$left->appendChild($this->importContent('<h3>'.__('Account details').'</h3>'));
		$left->setAttribute('class', 'span5');

		$this->account->tags = implode(', ', $this->account->tags);

		$block = new \helper\html\HTMLMerger('
		<div class="app-box">
			<form method="post" action="/accounting/updateAccount">
				<input type="hidden" name="_id" id="_id" />
				<label>kode</label>
				<input type="text" name="code" id="code" class="span4"  />

				<label>Tags</label>
				<input type="text" name="tags" id="tags" class="span4" />

				<label>Navn:</label>
				<input type="text" name="name" id="name" class="span4" />

				<label>Valuta</label>
				<input type="text" name="currency" id="currency" class="span4" />

				<label>Momskode</label>
				<input type="text" name="vatCode" id="vatCode" class="span4" />

				<label>Type</label>
				<select class="span4" name="type" id="type">
					<option value="4">Indtægter</option>
					<option value="3">Udgifter</option>
					<option value="1">Aktiver</option>
					<option value="2">Passiver</option>
				</select>

				<label>Beholdning</label>
				<input type="checkbox" name="allowPayments" id="allowPayments" class="span4" />

				<label>Kapitalkonto</label>
				<input type="checkbox" name="isEquity" id="isEquity" class="span4" />

				<div class="clearfix" />

				<input type="submit" value="Gem" class="pull-right btn btn-success btn-large" />

				<div class="clearfix" />
			</form>
		</div>
		', $this->account);

		$left->appendChild($this->importContent($block));
		$content->appendChild($left);

		/**** create right side ****/
		$right = $this->dom->createElement('div');
		$right->appendChild($this->importContent('<h3>'.__('Postings on account').'</h3>'));
		$right->setAttribute('class', 'span7');

		$table = new \helper\layout\Table(array(
			'account' => __('Account'),
			'.' => array(__('Amount'), function($o){
				return new \DOMText(($o->positive ? '' : '') . $o->amount);
			}),
		));
		$table->setEmpty(__('No postings on this transaction.'));
		$table->setIterator($this->postings);

		$right->appendChild($this->importContent($table));

		$content->appendChild($right);

		$holder->appendChild($content);

		return $holder;
	}
}
