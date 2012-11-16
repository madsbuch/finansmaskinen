<?php
/**
* Handling gruops through auth array
*
* this class contains no permission checking. that is supposed to be done in
* helper_core. this is due, to the fact that some functionality needs escalated
* previleges (those in start).
*/
namespace core;
class groups{
	
	/*********** FOR SINGLETON ***********/
	// Hold an instance of the class
	private static $instance;

	// The singleton method
	public static function getInstance() {
		if (!isset(self::$instance)){
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	// Prevent users to clone the instance
	public function __clone(){
	  trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	/********* THE CLASS ***************/
	
	private function __construct(){
		
	}
	/**
	* sets struct referencing to that i auth
	*/
	function setStruct(&$auth){
		$this->authArray = &$auth;
		//var_dump($auth);
	}
	
	/************************ MUTATOR FUNCTIONS *******************************/
	/**
	* functions from here do remove and add operations
	*
	* those set a flag, so that the struct are to be extracted at next pageload:
	*		$this->authArray['update'] = true;
	*/
	
	/**
	* create new group
	*
	* if mainGroup is set, the group is a main group, $parrent is then a 
	* supergroup
	*/
	public function createGroup($parent = -1){
		//validate
		if(!is_int($parent))
			throw new \Exception('Dafuq?!');
		
		//note we are updating
		if(isset($this->authArray->update))
			$this->authArray->update = true;
			
		$db = $this->getDB();
		
		if($parent > 0)
			$insert = 'INSERT INTO grp (parent, tree_id) SELECT id, tree_id FROM grp WHERE id = '.$parent.';';
		else
			$insert = 'INSERT INTO grp SET parent = -1;
				UPDATE grp SET tree_id = LAST_INSERT_ID() WHERE id = LAST_INSERT_ID()';
		
		$stmt = $db->query($insert);
		
		return (int) $db->dbh->lastInsertId(); 
	}
	
	/**
	* remove group
	*/
	public function removeGroup($id, $removeChildren=true){
		$this->authArray['update'] = true;
		$db = $this->getDB();
		
		if($removeChildren){
			foreach($this->authArray['groups'][$id]['children'] as $gid){
				//@TODO perfomance: do not call itself, add children to array,
				//and remove all at once
				$this->removeGroup($gid, $removeChildren=true);
			}
		}
		
		$db->insert('grp', array('id', $id));
	}
	
	/**
	* add a user to a group
	*
	* @param permissions	array of ints, if more
	*/
	public function addUser2Group($uid, $gid, $permissions){
		$this->authArray['update'] = true;
		$db = $this->getDB();
		$db->insert(array(
			'usr_id' => $uid,
			'grp_id' => $gid), 'usr_grp');
		
		//@TODO optimization
		if(is_array($permissions)){
			foreach($permissions as $perm){
				$db->insert(array(
				'usr_id' => $uid,
				'grp_id' => $gid,
				'permission' => $perm), 'usr_grp_permissions');
			}
		}
		else{
			$db->insert(array(
			'usr_id' => $uid,
			'grp_id' => $gid,
			'permission' => $permissions), 'usr_grp_permissions');
		}
	}
	
	/**
	* remove user from group
	*/
	public function removeUserFromGroup($uid, $gid){
		$this->authArray['update'] = true;
	}
	
	/**
	* Add app to group
	*/
	function addApp2Group($appID, $groupID, $expire = '9999999999', $status=true){
		if(is_object($this->authArray))
			$this->authArray->update = true;
			
		$db = $this->getDB();
		return $db->insert(array(
			'app_id' => $appID,
			'grp_id' => $groupID,
			'status' => $status,
			'expire' => $expire), 'app_grp');
		
		
	}
	
	/**
	* remove groups access to app
	*/
	function removeAppFromGroup($grp, $app){
		if(is_object($this->authArray))
			$this->authArray->update = true;
	}
	
	/**
	* return metadata for a group
	*/
	public function getMeta($grp){
		throw new \Exception('not yet implemented');
	}
	
	/**
	* set metadata for grp
	*/
	public function setMeta($grp, $key, $value){
		if(is_object($this->authArray))
			$this->authArray->update = true;
		$db = $this->getDB();
		return $db->insert(array(
			'gid' => $grp,
			'name' => $key,
			'value' => $value), 'grp_meta');
	}
	
	/***************************** SELECTOR FUNCTIONS *************************/
	/**
	* functions down from here are to select different values
	*/
	
	//APPS
	
	/**
	* get All Groups for app, also descendants
	*
	* @param	$app: name
	* @param	$object whether the complete groups objects are returned
	* @return	array of grpID
	*/
	public function getGrpForApp($app, $object = false){
		if(isset($this->authArray->appnames[$app]->groups)){
			$grps = $this->getGrpRecurse($this->authArray->appnames[$app]->groups);
			if(!$object)
				return $grps;
			else{
				$ret = array();
				foreach($grps as $g){
					$ret[] = $this->authArray->groups[$g];
				}
				return $ret;
			}
		}
		return false;
	}
	/**
	* takes an array, and recurses down the structure to fetch all descendants
	*/
	private function getGrpRecurse($grps){
		//run through all 
		foreach($grps as $g){
			$dGrps = array();
			if(!is_null($d = $this->authArray->groups[$g]->children))
				$dGrps = $this->getGrpRecurse($d);
			$grps = array_merge($grps, $dGrps);
		}
		return $grps;
	}
	
	/**
	* returns treeid
	*/
	public function getTree(){
		return $this->authArray->treeID;
	}
	
	/**
	* getGroups
	*
	* returns all groups for logged in user
	*/
	public function getGroups(){
		return $this->authArray->groups;
	}
	
	/**
	* this returns a group from a string
	*
	* read write and create are allowed on all objects in this groups
	*/
	public function getCommonGroup($group){
		//fetch to see if a group exists
		$db = $this->getDB();
		$sth = $db->dbh->prepare("SELECT * FROM grp_public WHERE identifier = ?");
		$sth->execute(array($group));
		$data = $sth->fetchAll();
		
		//if so, return the id of it and authenticate
		if(count($data) > 0){
			$this->authenticatePublicGroup($data[0]['grp_id'], $group);
			return $data[0]['grp_id'];
		}
		//if no common group? we create it!
		$grp = $this->createGroup();
		$sth = $db->dbh->prepare("INSERT INTO `core`.`grp_public` 
			(`identifier`, `grp_id`) VALUES
			(?, ?);");
		$sth->execute(array($group, $grp));
		
		//authenticate and return grp
		$this->authenticatePublicGroup($grp, $group);
		return $grp;
	}
	/**
	* aux for getCommonGroup
	*
	* provides NO VALIDATIOn
	*
	* and is therefor not public ;)
	*
	* permissions are to manipulate all objects
	*/
	private function authenticatePublicGroup($group, $name){
		if(!isset($this->authArray->auxGroups[$group])){
			$permissions = array(
				\config\constants::READ,
				\config\constants::WRITE,
				\config\constants::CREATE
			);
			$this->authArray->auxGroups[$group] = new \model\core\Group(array(
				'permissions' => $permissions,
				'id' => $group,
				'metaInfo' => array(
					'key' => $name,
					'note' => 'public group'
				),
				'parent' => -1,
				'isFetched' => true,
			));
		}
	}
	
	/**
	* Get permissions
	*
	* returns permission to given group (to this user)
	*/
	public function getPermissions($grp, $permission){
		if(	in_array($permission, $this->authArray->groups[$grp]->permissions)
			|| in_array('-1', $this->authArray->groups[$grp]->permissions))
			return true;
		return false;
	}
	
	/**
	* getChilds
	*
	* get all childs of a group
	*
	* @param $mode L | H : L for list H for hirachy
	*
	* array(
	*	id = id
	*	parrent = id
	* )
	*
	*/
	function getChilds($grpID, $mode = "H", $parrents = array()){
		
		//udtrække info om grpID
		$grp = $this->db->getRow("grp", "id = ".$grpID);
		
		$p = $grp['parent'];
		
		if($mode == "L")
			$parrents[] = $grp;
		elseif($mode == "H"){
			$grp['parent'] = $parrents;
			$parrents = $grp;
		}
		
		if($p < 0)
			return $parrents;
		return $this->getChilds($p, $mode, $parrents);
	}
	
	/**
	* retrieve DB connection
	*/
	private function getDB(){
		if(isset($this->db))
			return $this->db;
		else{
			$this->db = new \core\db(\config\config::$coreConfig);
			return $this->db;
		}
	}
}
?>
