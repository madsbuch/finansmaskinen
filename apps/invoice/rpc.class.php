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
		$invoice = $this->invoiceObject($invoice);
		$invoice = \api\invoice::create($invoice);
		$this->ret((string) $invoice->_id);
	}

    /**
     * creates invoice from a SimpleInvoice object,
     *
     * this offloads a lot of work to the server, but requires all products and
     * stuff to be stored here.
     *
     * @param $simpleInvoice
     * @return void
     * @internal param $easyInvoice
     */
	function simpleCreate($simpleInvoice){
        $invoice = new \model\finance\invoice\SimpleInvoice($simpleInvoice);
        $invoice = \api\invoice::simpleCreate($invoice);
        $this->ret((string) $invoice->_id);
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
	 * if the invoice is a draft, the system will finalize it (adding invoice number)
	 *
	 * @param $id
	 * @param $asset
	 * @param null $amount
	 * @return void
	 */
	function post($id, $asset, $amount = null){
		\api\invoice::bookkeep($id, $asset, $amount);
		$this->ret(array('success' => true));
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
