<?php
/**
* API class for use
*
* this file is not version named, so this is by default v. 1
*/

namespace api;
class invoice{
	/*************************** FRAMEWORK API CALLS **************************/
	
	/**
	* dependencies of this module
	*/
	public static $dependencies = array('contacts', 'companyProfile', 'products', 'accounting');
	
	
	/**
	 * mapping and ordering of legalNumbers
	 *
	 * should this be a part of contact?
	 * should this be decideable for the user?
	 */
	public static $legalEntities = array(
		'DKEAN' => 'DK:EAN',
		'DKCVR' => 'DK:CVR'
	);
	
	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle(){
		return "Faktura";
	}
	
	/**
	* get description
	*
	* returns user readable description of app (in users language)
	*/
	static function getDescription(){
		return "Se og administrer dine fakturaer";
	}
	
	static function export(){}
	static function import(){}
	
	/**
	* handles a file. f.eks. used for integratio n with xml or so
	*/
	static function handleFile($file){
	
	}
	
	/**
	* the same as export?
	* or maybe this is a automated stuff, that backs up to some user defined
	* storage (ftp ect)
	*/
	static function backup(){}
	
	/*************************** Plugins to other apps ***********************/
	
	/**
	* returns widget for frontpage
	*/
	static function on_getWidget(){
		return new \app\invoice\layout\finance\Widget(self::get(null, 3, array('isPayed' => false)));
	}
	
	/**
	* returns latest activity to use for the contacts
	*/
	static function on_contactGetLatest($contact){
		return new \app\invoice\layout\finance\ContactWidget(
			self::get(null, 3, array('contactID' => (string) $contact->_id)), $contact);
	}
	
	static function on_getAppSettings($companyObject){
		return new \model\finance\company\AppSetting(array(
			'title' => 'Fakturering',
			'settingsModal' => new \app\invoice\layout\finance\misc\SettingsModal($companyObject),
			'modalID' => '#app_invoice_layout_finance_misc_SettingsModal'
		));
	}
	
	/**
	* yeah, we provide callback for ourself ;)
	*/
	static function on_getInvoicePostCreate($invoice){
		$tpls = self::getTemplates();
		$dlWidget = new \app\invoice\layout\finance\widgets\InvoiceDownloadWidget($invoice);
		$dlWidget->setTemplates($tpls);

		$sendW = new \app\invoice\layout\finance\widgets\InvoiceMailWidget($invoice);
		$sendW->setTemplates($tpls);

		return array(
			$dlWidget,
			$sendW);
	}
	
	/**
	* this are boxes shown, when the invoice is still a draft
	*/
	static function on_getInvoiceDraft($invoice){
		return new \app\invoice\layout\finance\widgets\Draft($invoice);
	}
	
	/************************** INTERNAL APP API CALLS ************************/
	
	/**** Getters ****/
	public static function get($sort = null, $limit = null, $condition = null){
		$lodo = new \helper\lodo('invoices', 'invoice');
		if($limit)
			$lodo->setLimit($limit);
		if($sort)
			$lodo->sort($sort);
		if($condition)
			$lodo->addCondition($condition);

		return $lodo->getObjects('\model\finance\Invoice');
	}

	/**
	 * @param $id the id of the invoice
	 * @return /model/finance/Invoice
	 */
	public static function getOne($id){
		$lodo = new \helper\lodo('invoices', 'invoice');
		$lodo->setReturnType('\model\finance\Invoice');
		return $lodo->getFromId($id);
	}
	
	/**
	* returns the actual invoice as XML (UBL)
	*/
	public static function getInvoiceAsXML($id, $validate=false, $stylesheet=null){
		$lodo = new \helper\lodo('invoices', 'invoice');
		$lodo->setReturnType('\model\finance\Invoice');
		$inv = $lodo->getFromId($id);
		
		//$convert = new \helper\model\XML();
		//$convert->prepare($inv->Invoice);
		
		$ubl = new \helper\parser\Ubl();
		$ubl->createFromModel($inv->Invoice);

		return $ubl->getXML();
	}
	
	
	/**
	* creates new invoice in the system
	*
	* @param \model\finance\Invoice $invoice
     * @return mixed
     */
	public static function create(\model\finance\Invoice $invoice){
		$lodo = new \helper\lodo('invoices', 'invoice');
		//create the object
		$invoice = self::invoiceObject($invoice);

		//fulltext indexed by reciever
		$lodo->setFulltextIndex(array('Invoice.AccountingCustomerParty.Party.PartyName'));
		$obj = $lodo->insert($invoice);

        if($obj->isPayed)
			return self::finalize($obj);
		return $obj;
	}

    /**
     * Insert a simpleinvoice object.
     *
     * Updating is throught the normal interface, so far
     *
     * @param \model\finance\invoice\SimpleInvoice $invoice
     * @throws \exception\UserException
     * @return invoice object
     */
    public static function simpleCreate(\model\finance\invoice\SimpleInvoice $invoice){
        //some validation
        if($ret = $invoice->validate($invoice::STRICT))
            throw new \exception\UserException(__('SimpleInvoice not validated: %s', implode(', ', $ret)));

        //map it to a new Invoice
        $full = new \model\finance\Invoice(array(
            'Invoice' => array(
                'DocumentCurrencyCode' => $invoice->currency
            ),
            'contactID' => $invoice->contactID,
            //tell that we use element id's
            'objectIDs' => false,
            'vat' => $invoice->vat,


        ));

        if(!empty($invoice->date))
            $full->Invoice->IssueDate = $invoice->date;
	    else
		    $full->Invoice->IssueDate = date('c');

        //do some initialization
        $full->product = array();
        $full->Invoice->InvoiceLine = array();

        foreach($invoice->products as $key => $prod){
            //the product object
	        $full->product->$key->index = $key;
            $full->product->$key->id = $prod->productID;

	        //the corrosponding object in the Invoice structure
	        $full->Invoice->InvoiceLine->$key->ID = $key;
            $full->Invoice->InvoiceLine->$key->InvoicedQuantity = $prod->quantity;
        }

        //from here the normal system should be able to handle the rest.
        return self::create($full, false);
    }

    /**
     * update an invoice
     *
     * @param $inv
     * @return \model\finance\Invoice
     */
    public static function update(\model\finance\Invoice $inv){
		$lodo = new \helper\lodo('invoices', 'invoice');
		//create the object
		$invoice = self::invoiceObject($inv);
		$lodo->setFulltextIndex(array('Invoice.AccountingCustomerParty.Party.PartyName'));
		$lodo->update($invoice);
		return $invoice;
	}

	/**
	 * inserts invoice number and stuff
	 *
	 * after this action, the invoice cannot be deleted, and invoice number cannot
	 * be updated
	 *
	 * @param \model\finance\Invoice $inv
	 * @return \model\finance\Invoice
	 * @throws \exception\UserException
	 * @throws \Exception
	 */
	public static function finalize(\model\finance\Invoice $inv){
		//check if finalization is done
        if(isset($inv->Invoice->ID))
            return $inv;

		//finalization of invoice requires it to be a valid structure.
		if($ret = $inv->validate($inv::WEAK))
			throw new \exception\UserException(__('The invoice was not validated: %s', implode(', ', $ret)));

		//try to add invoice number, or fail
		$iNr = \api\companyProfile::increment('invoiceNumberNext');
		if(is_null($iNr))
			throw new \Exception('Not able to increment invoicenumber');
		
		//return updated invoice
		$inv->Invoice->ID = $iNr;
		
		return $inv;
	}
	
	/**
	 * bookkeeps invoice.
	 *
	 * marks the invoice as payed, and notes the reference
	 *
	 * @param $id string the id of the invoice
	 * @param $asset int the asset account the money is recived on.
	 * @param $amount int if the invoice is of different currency than the asset
	 *					account,  this is required.
	 */
	static function bookkeep($id, $asset, $amount = null){
		//fetch invoice
		$inv = self::getOne($id);

		//make sure the invoice is finalized before attempting to post to any systems
		if(empty($inv->Invoice->ID)){
			$inv = self::finalize($inv);
			$inv->draft = false;
			$inv = self::update($inv);
		}

		//model\finance\products\Catagory to account to
		$cats = array();
		
		//iterate through products
		if(!empty($inv->product)){
			foreach($inv->product as $i => $prod){
				$p = \api\products::getOne($prod->id);
				//no product was to find, we continue...
				if(!$p)
					continue;
				
				//we haven't seen this catagory before, so add it.
				if(!isset($cats[$p->catagoryID])){
					$cats[$p->catagoryID] = \api\products::getCatagory($p->catagoryID);
					$cats[$p->catagoryID]->accountAssert = $asset;
					//initialize raw amount
					$cats[$p->catagoryID]->amount = 0;
					$cats[$p->catagoryID]->vat = $inv->vat;
				}
				
				//note raw value to post to this catagory
				$cats[$p->catagoryID]->amount += $inv->Invoice->InvoiceLine->$i->LineExtensionAmount->_content;
			}
		}
		//post the cat's the to accounting system
		$inv->ref = __('Invoice %s', (string) $inv->Invoice->ID);

	    //todo bookkeep to product system


		//post to financial system
		$options = array(
			'referenceText' => $inv->ref,
			'type' => 'ProductCategory'
		);
		\api\accounting::importTransactions($cats, $options);

		//everything was an success
		$inv->isPayed = true;
		self::update($inv);
	}
	
	/**
	 * ok, as so far we only have a single layout. Later on we'll get XML transform to work
	 *
	 * returns all templates available for transforming the invoice
	 */
	public static function getTemplates($search = null){
		return new \model\finance\invoice\Template();
	}

    /**
     * takes invoice and a template, and transforms to some fileoutput
     * for now it only uses some standard template
     *
     *
     * @param $invoice either an invoice object or id of invoice
     * @param string $template either a template id, object, html or pdf
     *                    for HTML and CSS a default tempalte is used
     * @param string $output
     * @throws \Exception
     * @return mixed
     */
	public static function transform($invoice, $template = 'default', $output = 'html' ){
		$inv = self::getOne($invoice);

		$file = \helper\transform\Model::create($inv);

		if($template == 'default')
			$file = $file->Savant(__DIR__.'/templates/default.tpl.php');
		else
			throw new \Exception('Not yet implemented');

		if($output == 'html')
			$file = $file->generate();
		elseif($output == 'pdf')
			$file = $file->PDF('html')->generate();
		else
			throw new \Exception('Not yet implemented');
		
		return $file;
		//apply caching
	}

    /**
     * @param $invoiceID string id of invoice
     * @param $recipients array array of recipient emails
     * @param $subject
     * @param $message
     * @param null $template template to user
     * @throws \exceptions\UserException
     */
	public static function email($invoiceID, $recipients, $subject, $message, $template = null){
		$mail = new \helper\mail();

        foreach($recipients as $r){
            $mail->AddAddress($r);
        }

        $mail->AddReplyTo('noreply@finansmaskinen.dk', 'Finansmaskinen');
        $mail->SetFrom('noreply@finansmaskinen.dk', 'Finansmaskinen');

        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AddStringAttachment(self::transform($invoiceID, $template, 'pdf'), 'faktura.pdf');
        if(!$mail->Send())
            throw new \exceptions\UserException(__('Something went wrong.'));
	}

	/**
	 * This function does following to the invoice:
	 *  - parses all data
	 *  - validates that required fields are defined
	 *  - fetches data from other parts of the system to emrge in
	 *  - calculates totals
	 *
	 * if we talk an update, $old should contain the old invoice (which should be
	 * a full object)
	 *
	 * @param \model\finance\Invoice $inv
	 * @throws \exception\UserException
	 * @return \model\finance\Invoice
	 */
    private static function invoiceObject(\model\finance\Invoice $inv){
		$core = new \helper\core('invoice');
		// merge following details in:
		//accountingSupplierParty, no reason to play with permissions
		$supplier = \api\companyProfile::getPublic($core->getTreeID());

	    //data integrity
	    $inv->parse();

		//performing parsing on structure
		//strict validation is done upon creation, is UBL is to be used
		if(count($errs = $inv->validate($inv::WEAK)) > 0)
			throw new \exception\UserException(__("Validation errors on invoice:\n * %s\n ", implode("\n * ", $errs)));
		$inv->parse();


		//merge supplier data in
		$toMerge = array();
		$toMerge['Invoice']['AccountingSupplierParty']['Party'] = $supplier->Party->toArray();
		$toMerge['Invoice']['PaymentMeans'][0] = $supplier->PaymentMeans->toArray();
        if(!isset($inv->isPayed))
            $inv->isPayed = false;

		if(isset($inv->Invoice->IssueDate->_content))
			$toMerge['Invoice']['PaymentMeans'][0]['PaymentDueDate']['_content'] = ($supplier->dueDays
				* 86400) + $inv->Invoice->IssueDate->getUnixTime();
		
		//merge customer in
		$inv = self::mergeContact($inv);
	    $inv = self::mergeProducts($inv);

	    //all id's are rewritten to object ones, save that they are so
	    $inv->objectIDs = true;

		//merge data in
		$inv->merge($toMerge);

		//finalize, if finished
        if(!$inv->draft)
            $inv = self::finalize($inv);

	    //make sure data integrity is as should be
	    $inv->parse();

		return $inv;
	}

	/**
	 * @param \model\finance\Invoice $inv
	 * @return \model\finance\Invoice
	 */
	private static function mergeContact(\model\finance\Invoice $inv){
	    //merge supplier data in
	    $toMerge = array();
	    //merge customer in
	    if(!empty($inv->contactID)){
		    if($inv->objectIDs)
			    $contact = \api\contacts::getContact($inv->contactID);
		    else{
			    $contact = \api\contacts::getByContactID($inv->contactID);
			    $inv->contactID = (string) $contact->_id;
		    }
		    $party = $contact->Party;
		    $toMerge['Invoice']['AccountingCustomerParty']['Party'] = $party->toArray();

		    //merge leagalnumbers in
		    foreach(self::$legalEntities as $id => $val){
			    if(isset($contact->legalNumbers->$id)){
				    $toMerge['Invoice']['AccountingCustomerParty']['Party']['PartyLegalEntity']
				    ['CompanyID']['_content'] = $contact->legalNumbers->$id;
				    $toMerge['Invoice']['AccountingCustomerParty']['Party']['PartyLegalEntity']
				    ['CompanyID']['schemeID'] = $val;
			    }
		    }
	    }

	    $inv->merge($toMerge);
	    return $inv;
    }

	/**
	 * @param \model\finance\Invoice $inv
	 * @return \model\finance\Invoice
	 */
	private static function mergeProducts(\model\finance\Invoice $inv){
	    $toMerge = array();

	    //some totals for the products:
	    $total = 0;
	    $vat = 0;
	    if(!empty($inv->product)){
		    foreach($inv->product as $i => &$prod){
			    //fetch external product
			    if(!empty($prod->id) && $inv->objectIDs)
				    $p = \api\products::getOne($prod->id);
			    else{
				    $p = \api\products::getByProductID($prod->id);
				    $prod->id = (string) $p->_id;
			    }

			    $unitPrice = null;
			    //support for unitprice from the products
			    if(!isset($inv->Invoice->InvoiceLine->$i->Price->PriceAmount)){
				    //TODO handle currencies (should we make a currency module allowin people to define their own?)
				    $unitPrice = $p->Price->PriceAmount->_content;
				    $toMerge['Invoice']['InvoiceLine'][$i]['Price']['PriceAmount'] = $p->Price->PriceAmount;

				    //TODO when parse is fully implemented, this should be taken into account
				    $toMerge['product'][$i]['origAmount'] = $p->Price->PriceAmount->_content;
				    $toMerge['product'][$i]['origValuta'] = $p->Price->PriceAmount->currency;
			    }
			    else
				    $unitPrice = $inv->Invoice->InvoiceLine->$i->Price->PriceAmount->_content;

			    //for calculating total
			    $total += $t = $unitPrice * $inv->Invoice->InvoiceLine->$i->InvoicedQuantity->_content;


			    if($p)//make it possible to make an invoice on not saved products
				    $toMerge['Invoice']['InvoiceLine'][$i]['Item'] = $p->Item->toArray();

			    //set LineExtensionAmount
			    $toMerge['Invoice']['InvoiceLine'][$i]['LineExtensionAmount'] = $t;

			    //here we need the taxcatagory class
			    $tc = $inv->vat ? 'inclVat' : 'exclVat';
			    $vatAcc = $p->$tc;

			    //some taxes:
			    if($vatAcc){
				    $toMerge['Invoice']['InvoiceLine'][$i]['TaxTotal']
				    ['TaxSubtotal']['TaxCategory']['Percent']['_content']
					    = $vatAcc->percentage;
				    $toMerge['Invoice']['InvoiceLine'][$i]['TaxTotal']
				    ['TaxSubtotal']['TaxCategory']['ID']
					    = $vatAcc->taxcatagoryID;
				    $vat +=
				    $toMerge['Invoice']['InvoiceLine'][$i]['TaxTotal']['TaxSubtotal']['TaxAmount'] =
				    $toMerge['Invoice']['InvoiceLine'][$i]['TaxTotal']['TaxAmount'] =
					    ($t * (string) $vatAcc->percentage) / 100;

				    $toMerge['Invoice']['InvoiceLine'][$i]['TaxTotal']['TaxSubtotal']['TaxableAmount'] = $t;
			    }
		    }
	    }

	    //populate to full UBL invoice

	    //set MonetaryTotal
	    $toMerge['Invoice']['LegalMonetaryTotal']['PayableAmount']['_content'] = $total+$vat;
	    $toMerge['Invoice']['LegalMonetaryTotal']['LineExtensionAmount']['_content'] = $total;
	    //@TODO implement rabatter
	    $toMerge['Invoice']['LegalMonetaryTotal']['AllowanceTotalAmount']['_content'] = 0;

	    //some tax totals
	    $toMerge['Invoice']['TaxTotal'][0]['TaxSubtotal']['TaxableAmount']['_content'] = $total;
	    $toMerge['Invoice']['TaxTotal'][0]['TaxSubtotal']['TaxAmount']['_content'] = $vat;

	    $inv->merge($toMerge);
	    return $inv;
    }
	
}

?>
