<?php
/**
* a lodo data object
*
* this object SHOULD ONLY be created inside og the helper_lodo.
*/

namespace helper_lodo;

class obj{
	
	/**
	* this is the entry id from the database
	* this is set at construction time.
	*/
	private $id;
	
	/**
	* pointer to core object
	*/
	private $core;
	
	/**
	* groups the element applies to
	*
	* remember to check permissions
	*/
	private $groups;
	
	/**
	* array of details, that are changes
	*/
	private $change;
	
	/**
	* database object pointer
	*/
	private $DB;
	/**
	* the data array
	*
	* structure as follow:
	* $data
	*/
	private $data;
	private $list;
	
	/**
	* some constants
	*/
	
	const INSERT = 1;
	const DELETE = 2;
	const UPDATE = 3;
	
	/**
	* information about the databasetable
	*/
	private $entryTable;	//primary nabtable (grpID, nabID and a name for that specific nab)
	private $varcharTable;		//key value table (small strings as value) and typeRow
	private $intTable;	//table containing more data (notes) data > 255, and typeRow
	private $table;
	
	/**
	* populate the object
	*
	* @param	id			ID of lodo object
	* @param	groups		array of groups this object should check permissions
							from. Permissions are appended to each other. 
	*/
	public function __construct($id, $groups, $table, $coreObj){
		//set some private variables
		$this->id = $id;
		$this->groups = $groups;
		$this->entryTable 	 = $table;
		$this->table 	 = $table;
		$this->varcharTable	 = $table."_data";
		$this->intTable	 = $table."_intData";
		
		$this->core = $coreObj;
		$this->DB = $this->core->getDB();
		
		//fetch the data
		$this->populate();
	}
	
	/******************** PUBLIC FUNCTIONS ************************************/
	
	/**
	* returns all information
	*/
	function getAll($type=null){
		return $this->list;
	}
	
	/**
	* returns some info
	*/
	function getInfo($type, $name){
		return $this->data[$type][$name]['value'];
	}
	
	/**
	* this function should encode the ID in some way, to make it harder for
	* people to read.
	*/
	function getID(){
		return $this->id;
	}
	
	/**
	* set info
	*/
	function setInfo($type, $name, $value){
		$grps = $this->getAuthorizedGroups(\config_const::WRITE);
		if(count($grps) < 1){
			trigger_error("NOT ALLOWED");
			return false;
		}
		
		$this->data[$type][$name]['value'] = $value;
		$this->data[$type][$name]['type'] = $type;
		$this->data[$type][$name]['name'] = $name;
		$this->data[$type][$name]['grps'] = $grps;
		$this->data[$type][$name]['updated'] = false;
		$this->data[$type][$name]['operation'] = self::INSERT;
		$this->change[] = &$this->data[$type][$name];
		
	}
	
	/**
	* remove piece og info
	*/
	function remInfo($type, $name){
		$this->data[$type][$name]['operation'] = self::DELETE;
		$this->change[] = &$this->data[$type][$name];
	}
	
	/**
	* add relation between this object and a group
	*
	* either int or array
	*/
	public function addGroup($grp){
		$toInsert = array();
		
		//if grp is an array
		if(is_array($grp)){
			foreach($grp as $g){
				$toInsert[] = array($this->id, $g);
			}
			//insert
			$this->DB->insert($toInsert, $this->table."_entry_grp", array('entry_id', 'grp_id'));
			return true;
		}
		//otherwise we see it as a number
		else{
			$grp = (int) $grp;
			return $this->DB->insert(array('entry_id' => $this->id, 'grp_id' => $grp), $this->table."_entry_grp");
		}
	}
	
	/**
	* search in fields based on type
	*/
	public function searchByType($type, $match, $regexp=false){
	
	}
	
	/**
	* search in all fields
	*/
	public function searchAll($match, $regexp=false){
	
	}
	
	/************************* DATABASE SYNCHRONIZATION ***********************/
	
	/**
	* apply data to the database
	*/
	public function apply(){
		foreach($this->change as $row){
			//setting varcharTable to use
			if($row['type'] % 2 == 0)
				$table = $this->varcharTable;
			else
				$table = $this->intTable;
			
			switch($row['operation']){
				case self::INSERT :
					$objID = $this->DB->insert(array(
						'entry_id' 	=> $this->id,
						'key' 		=> $row['name'],
						'value' 	=> $row['value'],
						'fieldtype'	=> $row['type']
						), $table);
				break;
				case self::UPDATE :
				
				break;
				
				case self::DELETE :
				
				break;
			}
		}
	}
	
	/**
	* populate
	*
	* fetch data from cache or database
	*/
	public function populate($bigData=false){
		//atleast one of the used groups should have permissions to read.
		if(count($this->getAuthorizedGroups(\config_const::READ)) < 1)
			return false;
		
		//$this->varcharTable	 = $table."_data";
		//$this->intTable	 = $table."_bigData";
		
		$list = $this->DB->getList($this->varcharTable, array('entry_id', $this->id));
		
		foreach($list as $row){
			$this->data[$row['fieldtype']][$row['key']]['value'] = $row['value'];
			$this->data[$row['fieldtype']][$row['key']]['type']  = $row['fieldtype'];
			$this->data[$row['fieldtype']][$row['key']]['name']  = $row['key'];
			$this->data[$row['fieldtype']][$row['key']]['id']    = $row['id'];
			$this->list[] = &$this->data[$row['fieldtype']][$row['key']];
		}
		
	}
	
	
	/************************* FOR LODO OBJECT SYNCHRONIZATION ****************/
	
	/**
	* get change stack
	*
	* get the stack of changes since $last
	*/
	public function getStack($last){
		
	}
	
	/******************************** AUX *************************************/
	
	private function getAuthorizedGroups($perm){
		$toWriteTo = array();
		foreach($this->groups  as $g){
			if($this->core->isAllowed($g, $perm))
				$toWriteTo[] = $g;
		}
		return $toWriteTo;
	}
}

?>
