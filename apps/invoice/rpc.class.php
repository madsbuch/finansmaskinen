<?php
/**
* dispatching Remote Procedure Calls
*/

namespace rpc;

class invoice extends \core\rpc {
	
	public $docs = array(
		'add' => 'adds invoice from the Invoice model. Returns the invoice',
		'addUBL' => 'takes an UBL invoice, and inserts it.',
		
		'pay' => 'takes invoice ID and sets state as payed'
	);
	
	
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	 * Creates an invoice
	 *
	 * The invoice must be a valid OIOUBL invoice dokument
	 *
	 */
	function create($invoice){
		try{
			$invoice = $this->invoiceObject($invoice);

			$invoice = \api\invoice::create($invoice);
			
			$this->ret((string) $invoice->_id);
		}
		catch(\exception\UserException $e){
			$this->throwException($e->getMessage());
		}
	}

	/**
	 * creates invoice from a SimpleInvoice object,
	 *
	 * this offloads a lot of work to the server, but requires all products and
	 * stuff to be stored here.
	 *
	 * @param $easyInvoice
	 */
	function createLight($easyInvoice){

	}

	function update($invoice){
		$this->throwException("not yet implemented");
	}

	function get($id){
		$this->throwException("not yet implemented");
	}

	/**
	 * post an invoice to the accounting
	 *
	 * @param $id
	 */
	function post($id){
		$this->throwException("not yet implemented");
	}

	/**
	 * performs some operations on the invoice object
	 *
	 * @param $inv array
	 * @return \model\finance\Invoice
	 */
	private function invoiceObject($inv){
		return new \model\finance\Invoice($inv);
	}
}

?>
