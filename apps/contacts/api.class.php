<?php
/**
* API class for use
*
* this file is not version named, so this is by default v. 1
*/
namespace api;

class contacts extends \core\api{
	/*************************** FRAMEWORK API CALLS **************************/


	/**
	* getTitle
	*
	* Returns user friendly name of app (in current language)
	*/
	static function getTitle(){
		return __('Contacts');
	}
	
	/**
	* is called casually
	*
	* we don't have any groups here
	*/
	static function cron(){
		$lodo = new \helper\lodo('contacts', 'contacts');
		//optimize the collection
		$lodo->optimizeCollection(array(
			'Party.PartyName'
		));

		//@TODO, take all contacts that have an external reference, to another
		//company, and update thei information
	}
	/*************************** LISTENERS ************************************/
	
	/**
	* not very often, here we do the DB optimization
	*/
	static function on_cron15days(){
		$lodo = new \helper\lodo('contacts', 'contacts');
		//optimize the collection
		$lodo->optimizeCollection(array(
			'Party.PartyName',
			'id'
		));
	}

	/*************************** EXTERNAL API FUNCTIONS ***********************/
	
	/**
	* calls from here are for commnicating with the system
	*
	*
	*/
	/**
	* returns contacts
	*
	* returns contacts associated to groups that the user is a part of. if not
	* contacts from alle groups are needed, $grp may be specified
	*
	* @param	sort	sort by indexed fields
	* @param	limit	how many contacts are to be returned?
	* @param	start	start offset for returning contacts
	* @param	grp		only contacts from specified groups this is an array
	*/
	static function getContactList($sort = array(), $limit=false, $start=0, $grp=false){
		$contacts = new \helper\lodo('contacts', 'contacts');
		if(!$grp)
			$contacts->setGroups($grp);
		if($limit)
			$contacts->setLimit($start, $start+$limit);
		else
			$contacts->setLimit($start);
		
		//$contacts->setReturnType('\\helper\\business\\Company');
		
		return $contacts->getObjects();
		
	}
	
	/**
	* damn, so bloated
	*
	* @param $search fulltext search
	* ...
	*/
	static function get($search=null,
						$sort = null,
						$condition = null,
						$limit = null,
						$start=null,
						$cursor=false){
		$contacts = new \helper\lodo('contacts', 'contacts');
		if($limit)
			$contacts->limit($limit);
		if($sort)
			$contacts->sort($sort);
		if($cursor)
			$contacts->returnCursor();
		
		if($search)
			$contacts->addFulltextSearch((string) $search);
		
		return $contacts->getObjects('\model\finance\Contact');
	}
	/**
	* actually alias of get, but the strips away alot of shit
	*/
	static function search($term, $sort=null, $limit=null){
		$contacts = new \helper\lodo('contacts', 'contacts');
		if($limit)
			$contacts->setLimit(0, $limit);
		if($sort)
			$contacts->sort($sort);
		
		$contacts->addFulltextSearch((string) $term);
		
		return $contacts->getObjects('\model\finance\Contact');
	}
	
	/**
	* this will probably be the future function...
	*
	* much more fleksibel
	*/
	static function getLodo(){
		return new \helper\lodo('contacts', 'contacts');
	}
	
	/**
	* return specific contact
	*
	* @param $format	specify return format. following are good:
	*					obj: herlper_lodo object
	*					asp: XML string of UBL SupplierParty type
	*					VCARD: VCARD string
	* @return	\model\finance\Contact
	*/
	static function getContact($id){
		$contact = new \helper\lodo('contacts', 'contacts');
		$contact->setReturnType('model\finance\Contact');
		return $contact->getFromId($id);
	}

    static function getByContactID($id){
        $lodo = new \helper\lodo('contacts', 'contacts');
        $lodo->setReturnType('model\finance\Contact');
        $lodo->addCondition(array('contactID' => (string) $id));
        $ret = $lodo->getObjects();
        if(count($ret) < 1)
            return null;
        return $ret[0];
    }
	
	/**
	* insert contact
	*
	* appended to all available groups, if none specified
	* @param $data mixed	either a UBL cocument string, a UBL parser object
	*						VCARD or a lodo object. if false, a lodo object for
	*						population is returned
	*
	* @TODO maḱe create aware
	*/
	static function create(\model\finance\Contact $data){
        $data = self::contactObj($data);
        $lodo = new \helper\lodo('contacts', 'contacts');
		$core = new \helper\core('contacts');
		
		$core->notify(__('Contact %s created', $data->name));

        //@TODO check uniq id, and add if none

		$lodo->setFulltextIndex(array('Party.PartyName.Name._content'));
		$obj = $lodo->insert($data);
		
		return $obj;
	}
	
	/**
	* update contacts
	*/
	static function update($newContact){
        $newContact = self::contactObj($newContact);
		$lodo = new \helper\lodo('contacts', 'contacts');
		$lodo->setReturnType('\model\finance\Contact');
		$lodo->setFulltextIndex(array('Party.PartyName.Name._content'));
		return $lodo->update($newContact);
	}
	
	/**
	* used for autocomplete forms
	*
	* default type = 
	*/
	static function getAutocompleteList($keyword, $type=false){
		if($type == false)
			$type = self::COMPANY;
	}
	
	/**
	* takes an contact, and retrives any external references
	*/
	static function retrieveExternal($id){
		$contact = self::getContact($id);
		
		if(!isset($contact->apiID) || !isset($contact->apiUrl))
			throw new \exception\UserException('No external data on this contact');

		//do the retrival stuff
		$rpc = new \helper\rpc\Finance($contact->apiUrl . '/companyProfile', true);
		$toMerge = $rpc->getPublic($contact->apiID);
		
		$tm['Party'] = $toMerge['Party'];
		
		$contact->merge($tm, true);
		
		return self::update($contact);
	}
	
	/**** SOME PRIVATE STUFF ****/

    /**
     * prepares the contact
     *
     * @param $obj
     */
    private static function contactObj($obj){

        if(empty($obj->contactID)){
            if(isset($obj->Party->PartyName->Name))
                $p = (string) $obj->Party->PartyName->Name;
            else
                $p = base_convert(time(), 10, 36);
            $obj->contactID = strtoupper(substr($p, 0, 2));
            $obj->contactID .= '-'.(time() % 1000000);
        }

        $excl = null;
        if(!empty($obj->_id))
            $excl = (string) $obj->_id;

        if(self::idExists($obj->contactID, $excl))
            throw new \exception\UserException(__('ContactID is not unique.'));

        return $obj;
    }

    /**
     * @param $id string representation of the unique id to check if in the db
     * @param $exclude documents to be excluded (their mongoID's)
     */
    private static function idExists($id, $exclude = null){
        $obj = self::getByContactID($id);
        if(is_null($obj))
            return false;
        if((string) $obj->_id === $exclude)
            return false;
        return true;
    }
}

?>
