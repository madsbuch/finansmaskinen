<?php
/**
* API class for use
*
* this file is not version named, so this is by default v. 1
*/
namespace api;

class billing extends \core\api{
	/*************************** FRAMEWORK API CALLS **************************/
	
	/**
	* definition of some callback
	*
	* check http://code.google.com/p/phpplexus/
	*
	*/
	
	/**
	* returns a summery of this app in a widget
	*
	* @return \model\platform\Widget
	*/
	static function on_getWidget(){
		return new \app\billing\layout\finance\Widget(self::get(null, null, 4));
	}
	
	/**
	* returns latest activity to use for the contacts
	*/
	static function on_contactGetLatest($contactObj){
		//@TODO check permissions, user should have permission to view bills
		return new \app\billing\layout\finance\ContactWidget(null, $contactObj);
	}
	
	/**
	* getThumbnail
	*
	* returns link to thumbnail
	*/
	static function getThumbnail(){
		
	}
	
	/**
	* get accepted filetypes
	*
	* if the app handles files, these are the fileendings appepted
	*/
	static function getAcceptetFiletypes(){
		
	}
	
	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle(){
		return __('Bills');
	}
	
	/**
	* get description
	*
	* returns user readable description of app (in users language)
	*/
	static function getDescription(){
		return __('Administrate and pay your incomming bills');
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
	
	/*************************** EXTERNAL API FUNCTIONS ***********************/
	
	public static function getOne($id){
		$lodo = new \helper\lodo('bills', 'billing');
		$lodo->setReturnType('\model\finance\Bill');
		return $lodo->getFromId($id);
	}
	
	
	/**
	* creates new invoice in the system
	*
	* if the bill is payed, it'll be finalized as well
	*/
	public static function create($invoice){
		$lodo = new \helper\lodo('bills', 'billing');
		$core = new \helper\core('billing');
		
		// merge following details in:
		//accountingSupplierParty, no reason to play with permissions
		//$party = \api\companyProfile::getPublic($core->getTreeID())->Party;
		//$toMerge['Invoice']['AccountingSupplierParty']['Party'] = $party->toArray();
		
		//accountingCustomerParty
		//$party = \api\contacts::getContact($invoice->contactID)->Party;
		//$toMerge['Invoice']['AccountingCustomerParty']['Party'] = $party->toArray();
		
		//items from productlines
		
		//populate to full UBL invoice
		
		//$invoice->merge($toMerge);
		//fulltext indexed by reciever
		$lodo->setFulltextIndex(array('Invoice.AccountingSupplierParty.Party.PartyName'));
		$obj = $lodo->insert($invoice);
		
		if($obj->isPayed)
			return self::finalize((string) $obj->_id);
		return $obj;
	}
	
	/**
	* retrieves a bill from the database
	*/
	static function retrieve($id){

	}
	
	/**
	* returns bills
	*
	* returns bills associated to groups that the user is a part of. if not
	* bills from alle groups are needed, $grp may be specified
	*
	* @param	sort	sort by indexed fields
	* @param	limit	how many contacts are to be returned?
	* @param	start	start offset for returning contacts
	* @param	grp		only contacts from specified groups this is an array
	*/
	static function getList($sort = array(), $limit=false, $start=0){
		$bills = new \helper\lodo('bills', 'billing');
		if($limit)
			$bills->setLimit($start, $start+$limit);
		else
			$bills->setLimit($start, 10);
		
		//$contacts->setReturnType('\\helper\\business\\Company');
		
		return $bills->getObjects();
	}
	
	static function get($sort = null, $conditions=null, $limit=null){
		$bills = new \helper\lodo('bills', 'billing');
		if($limit)
			$bills->setLimit($limit);
		
		//$contacts->setReturnType('\\helper\\business\\Company');
		
		return $bills->getObjects();
	}
	
	/**
	* updates a bill
	*
	* be aware, that not all fields are updateable if the bill is posted. if that
	* is the case, unposting is an option.
	*/
	static function update($obj){
	
	}
	
	static function delete($id){
	
	}
	
	/**
	* post a bill to the accounting
	*
	* this action makes the bill undeleteable
	*/
	static function post($id){
	
	}
	
	/**
	* unpost a bill from the accounting
	*
	* this function creates the counterposts in the accounting, making it possible
	* to delete and update the bill again.
	* unposting is not possible if the accounting is closed
	*/
	static function unpost(){
	
	}
	
	/**
	* add bill as draft, from external source.
	*
	* this draft will have to
	*
	* @param $treeID the ID of the tree, where the bill is inserted
	* @param $bill the bill object.
	*/
	static function addExternal($treeID, $bill){
	
	}
}

?>
