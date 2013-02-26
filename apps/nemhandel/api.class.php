<?php
/**
* API class for use
*
* this file is not version named, so this is by default v. 1
*/

namespace api;
class nemhandel{
	/*************************** FRAMEWORK API CALLS **************************/
	
	/**
	* dependencies of this module
	*/
	public static $dependencies = array('contacts', 'companyProfile', 'products', 'accounting');

	
	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle(){
		return "Nemhandel";
	}
	
	/*************************** Plugins to other apps ***********************/

	/**
	 * show form for allowing to send invoice through nemhandel or displaying details if invoice
	 * was sent
	 *
	 * @param $invoice \model\finance\Invoice
	 * @return \app\invoice\layout\finance\ContactWidget
	 */
	static function on_getInvoicePostCreate($invoice){
		return new \app\nemhandel\layout\finance\widgets\InvoiceWidget($invoice);
	}

	/**
	 * returns widget that tells whether the invoice is validated, or if it
	 * needs some fields to be set.
	 *
	 * @param $invoice \model\finance\Invoice
	 */
	static function on_getInvoiceDraft($invoice){

	}

	/**** ACTUAL API METHODS ****/

	/**
	 * transforms and queues an invoice to send
	 *
	 * @param \app\invoice\layout\finance\ContactWidget $invoice
	 */
	static function SendQueue(\app\invoice\layout\finance\ContactWidget $invoice){

	}

	/**
	 * returns currenct status on invoice
	 *
	 * @param $invoiceID
	 */
	static function getSatus($invoiceID){

	}
	
	
}

?>
