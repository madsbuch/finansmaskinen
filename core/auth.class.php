<?php
/**
* Authentication class
*
* This class handles core level user authentication
* @author: Mads Buch
*/
namespace core;

class auth{
	/*********** FOR SINGLETON ***********/
	// Hold an instance of the class
	private static $instance;

	// The singleton method
	public static function getInstance() {
		if (!isset(self::$instance)){
			$c = __CLASS__;
			self::$instance = new $c;
		}
		
		//we have to check permissions. Only another core or a helper function must
		//call this function
		/*
		$trace=\debug_backtrace();
		$caller=\array_shift($trace);

		if (!isset($caller['class'])){
			throw new Exception('insufficient permission');
			return false;
		}
		
		$class = \explode("_", $caller['class']);
		*/
	/*	if($class[0] != "helper" || $class[0] != "core" || $class[0] != "start"){
			throw new Exception('insufficient permission');
			return false;
		}*/
		
		return self::$instance;
	}

	// Prevent users to clone the instance
	public function __clone(){
	  \trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	// A private constructor; prevents direct creation of object
	private function __construct(){
		//check for info in session (is any user logged in)?
		$session = session::getInstance();
		if(isset($session->auth_holder))
			$this->auth = $session->auth_holder;
		
		//initialising groups
		$groups = groups::getInstance();
		$groups->setStruct($this->auth);
	}
	
	function __destruct(){
		$session = session::getInstance();
		$session->auth_holder = $this->auth;
	}
	
	/********* THE CLASS ***************/

	/**
	* instance of \model\core\Auth
	*/
	private $auth;
	
	/******************** APP ABSTRACTIONS ************************************/
	
	/**
	* get apps for logged in user
	*/
	public function getApps(){
		if(isset($this->auth->appnames))
			return $this->auth->appnames;
		return null;
	}
	
	/**
	* returns trees this company has access to
	*/
	public function getTrees(){
		if(isset($this->auth->trees))
			return $this->auth->trees;
		return null;
	}
	
	/**
	* wether the app is authorized for logged in user
	*/
	public function appAuthorized($app){
		if(isset($this->auth->appnames[$app]))
			return true;
		return false;
	}
	
	/******************** GROUP ABSTRACTIONS **********************************/
	// found in core/groups.class.php
	
	/******************** ACTUAL AUTH *****************************************/
	
	/**
	* usually auth uses session as cache, but when using f.eks. api, session
	* is not a proper abstraction mechanism, therefor this provides custom
	* caching.
	*
	* this also works deferently, because this caches logins, rather than saves
	* them, this means that login(...) has to be used at every page load
	*/
	function setCache($cache){
	
	}
	
	/**
	* log current user out
	*/
	function logout(){
		$this->auth = null;
	}
	
	/**
	* isLoggedIn
	*
	* wether a user is logged in
	*/
	function isLoggedIn(){
		if(isset($this->auth->userID))
			return true;
		return false;
	}
	
	/**
	 * Log a user in to the system
	 *
	 * TODO add cache facilities, quite easy, create a file identified by uid and secret
	 * let it contain information. use it if it exists, delete the file if any changes are made.
	 */
	function login($uid, $secret){
		if(!$this->validateUser($uid, $secret))
			return false;
		
		//set variables
		$this->fetchInformation($uid);
		
		logHandler::statistic(logHandler::LOGIN);
		return true;
	}
	
	/**
	* if changes have been made, do a refetch
	*/
	function reFetch($tree = null){
		if(!isset($this->auth))
			return;
		
		$tree = $tree ? $tree : $this->auth->treeID;
		$this->fetchInformation($this->auth->userID, $tree);
	}
	
	/**
	* notes that a app is setup
	*/
	function doSetup($app){
		if(!isset($this->auth->appnames[$app]))
			return;
		
		//insert the row, that makes it possible
		$db = $this->getDB();
		$arr = array('app_id' => $this->auth->appnames[$app]->id,
					 'tree_id' => $this->auth->treeID);
		//do the actual insertion
		$db->insert($arr, 'app_setup');
		//alther the tree
		$this->auth->appnames[$app]->isSetup = $this->auth->treeID;
	}
	
	/**
	* get user id.
	*
	* should not be used in apps!! groups are used there
	*/
	public function getUID(){
		if($this->isLoggedIn())
			return $this->auth->userID;
		return false;
	}
	
	/**
	* use this for storing data associated to the user
	*/
	public function getPersonalGroup(){
		if($this->isLoggedIn())
			return $this->auth->personalGroup;
		return false;
	}
	
	/**
	* validates secret against userid
	*/
	public function validateUser($uid, $secret){
		$db = $this->getDB();
		
		//make sure uid is int
		$uid = (int) $uid;
		
		//get information about user
		$user = $db->getRow("usr", "id = $uid");
		
		//test if secret is correct
		if($secret === $user['secret'])
			return true;
		return false;
	}
	
	/**
	* getStruct
	*
	* this function is only intended for root pages (those in start folder)
	* it is higly recommended to use abstraction layers due to incombatability.
	* If you are a app developer, use helper_core!
	*/
	public function getStruct(){
		$page = inputParser::getInstance();
		
		//only Sites
		if($page->getSite != "index")
			return false;
		
		return $this->struct;
	}
	
	/**
	* Create new user
	*
	* this function creates a new user, and a personal group
	* 
	* @return array(uid => uid, secret => secret)
	*/
	public function createUser(){
		
		//@TODO use transactions ?!?
	
		//generate user secret access key
		$secret = $this->genSecureKey();
		
		//db handle
		$db = $this->getDB();
		
		//insert the user into the user table
		$arr = array('secret' => $secret);
		//insert statement, witch returns uid
		$userID = $db->insert($arr, 'usr');
		//do error checking
		if(!is_numeric($userID) && $userID >= 0){
			trigger_error("Failed to create user.", E_USER_ERROR);
			return false;
		}
		//get grp object
		$groups = groups::getInstance();
		
		//create users personal group
		$grpID = $groups->createGroup();
		//error checking
		if(!is_numeric($grpID) || $grpID <= 0){
			trigger_error("Failed to create primary group for user: $userID", E_USER_ERROR);
			return false;
		}
		
		//generate public key
		$str = str_split($this->genSecureKey(), 20);
		$public = $str[0].$grpID.$str[1].$userID.$str[2];
		
		//insert public key
		$db->update(array("public" => $public), "usr", "id = ".$userID);
		
		//insert main grp
		$db->update(array("main_grp" => $grpID), "usr", array('id', $userID));
		
		//add the user to the group
		$groups->addUser2Group($userID, $grpID, \permissions::ALL);
		
		return new \model\Base(array("uid" => $userID, "secret" => $secret));
	}
	
	/**
	* fetches information about apps and groups
	*
	* and puts it in to the auth struct
	*/
	private function fetchInformation($uid, $tree = -1){
		//unset auth
		$this->auth = null;
		
		//some initiating stuff
		$this->auth = new \model\core\Auth();
		
		//@TODO this is personal group
		$this->auth->userID = $uid;
		$this->auth->trees = array();
		
		//fetching all groups, the user has a direct relation to, and their permissions
		$sql = '
			SELECT grp.parent as parent, grp.id as grpid, grp.tree_id as treeid, perm.permission,
			usr.main_grp as main_grp
			FROM grp, usr_grp, usr_grp_permissions as perm
			RIGHT OUTER JOIN usr ON usr.id = usr.id
			WHERE usr.id = \''.$uid.'\'
			AND usr_grp.usr_id = usr.id
			AND grp.id = usr_grp.grp_id
			AND perm.usr_id = usr.id
			AND perm.grp_id = grp.id
			ORDER BY grp.id ASC;;
			';//the sql statement
		
		//the code for executing the statement
		$db = $this->getDB();
		$stmt = $db->query($sql);
		$stmt->execute();
		
		//array over the groups
		$grps = array();
		
		//iterating through the rows
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			//first time we see this group?
			if(!in_array($row['grpid'], $grps)){
				//append it to the processing array
				$grps[] = $row['grpid'];
				//set some information in struct
				$this->auth->groups[$row['grpid']] = new \model\core\Group();
				
				$this->auth->groups[$row['grpid']]->treeID = $row['treeid'];
				$this->auth->groups[$row['grpid']]->parent = $row['parent'];
				$this->auth->groups[$row['grpid']]->id = $row['grpid'];
				$this->auth->groups[$row['grpid']]->isFetched = false;
				
				//setting main id, this is set for all rows
				$this->auth->mainGroup = $row['main_grp'];
				
				//setting some treeid
				if(!in_array($row['treeid'], $this->auth->trees) && $row['treeid'] != $row['main_grp']){
					$this->auth->trees[] = $row['treeid'];
					//@TODO, set this accordingly to parameter "tree"
					if(!isset($this->auth->treeID))
						$this->auth->treeID = $row['treeid'];
				}
					
			}
			//append permission to group
			$this->addPermission($row['grpid'], $row['permission']);
		}
		
		//apply details to all children
		foreach($grps as $g){
			//traverse up, and fetch all apps
			$apps	= $this->fetchApps($g);
			$perms	= $this->auth->groups[$g]->permissions;
			$this->applyInfo($g, $perms, $apps, array());
		}
		$this->applySorting();
		
		//var_dump($this->auth, strlen(serialize($this->auth)));
		//unset($_SESSION);
		//die();
	}
	
	/**
	* get apps for group
	*
	* this function fetches apps from higher nodes in the tree, and returns them
	* 
	* invariant:	this must be called before applyInfo. It relies on the
	*				isFetched variable.
	* 				
	*/
	private function fetchApps($group){
		//the apps are fetches with this group
		if($this->auth->groups[$group]->isFetched)
			return $this->auth->groups->apps;
		//we will fetch the group parent and the app information to this group
		$sql = '
			SELECT grp.parent, grp.tree_id , app.*, app_setup.tree_id as is_setup
			FROM grp 
			LEFT OUTER JOIN app_grp ON grp.id = app_grp.grp_id
			LEFT OUTER JOIN app ON app.id = app_grp.app_id
			LEFT OUTER JOIN app_setup ON app_setup.tree_id = grp.tree_id
			WHERE grp.id = \''.$group.'\'
			';//the sql statement
		
		//the code for executing the statement
		$db = $this->getDB();
		$stmt = $db->query($sql);
		$stmt->execute();
		
		//iterating through the rows
		$apps = array();
		$parent = -1;
		while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
			//add access to app
			$this->addApp($row['id'], $row['name']);
			//append app to return array
			if(!in_array($row['id'], $apps))
				$apps[] = $row['id'];
			
			//note whether the app is setup
			if(!is_null($row['id'])){
				if(is_null($row['is_setup']))
					$this->auth->apps[$row['id']]->isSetup = false;
				else
					$this->auth->apps[$row['id']]->isSetup = $row['is_setup'];
					
				$this->auth->apps[$row['id']]->requireSetup = $row['require_setup'];
			}
			
			$this->setSorting($row['id'], $row['sorting']);
			
			//well there is only one parent, so this is the same for every
			//iteratiorn
			$parent = $row['parent'];
		}
		if($parent < 0)
			return $apps;
		
		//get upper level apps, merge it and return
		$secondApp = $this->fetchApps($parent);
		$return=array();
		foreach($apps as $a)
			if(!in_array($a, $return))
				$return[] = $a;
		foreach($secondApp as $a)
			if(!in_array($a, $return))
				$return[] = $a;
		return $return;
	}
	
	/**
	* apply info to grp, and subgroups
	*
	* @param	$grp	the group to apply info to
	* @param	$perms	permissions to apply to groups
	* @param 	$apps	apps to apply
	*
	*/
	private function applyInfo($grp, $perms, $apps, $member, $self=true){
		
		//extend apps
		if($apps)
			foreach($apps as $a)
				$this->addAppToGroup($a, $grp);
		//extend permissions
		foreach($perms as $p)
			$this->addPermission($grp, $p);
		
		//is this node visited before?
		if($this->auth->groups[$grp]->isFetched){
			//extend apps
			if($apps)
				foreach($apps as $a)
					$this->addAppToGroup($a, $grp);
			//extend permissions
			foreach($perms as $p)
				$this->addPermission($grp, $p);
			//@TODO extend members
		}
		//first time visit for this grp
		else{
			//fetch information about group and children
			if($self)
				$s = 'OR grp.id = \''.$grp.'\'';
			else
				$s = '';
			$sql = '
			SELECT 	grp.id AS grpid,
			grp.tree_id as treeid,
			grp.parent AS grpParent,
			grp_meta.name,
			grp_meta.value,
			app.id AS appid,
			app.require_setup,
			app.sorting as sorting,
			app.name AS appname,
			app_setup.tree_id AS is_setup
			FROM grp 
			LEFT OUTER JOIN grp_meta ON grp_meta.gid = grp.id
			LEFT OUTER JOIN app_grp ON grp.id = app_grp.grp_id
			LEFT OUTER JOIN app ON app.id = app_grp.app_id
			LEFT OUTER JOIN app_setup ON app_setup.app_id = app.id AND app_setup.tree_id = grp.tree_id
			WHERE grp.parent = \''.$grp.'\'
			'.$s.'
			ORDER BY grp.id ASC;
			';//the sql statement
		
			//the code for executing the statement
			$db = $this->getDB();
			$stmt = $db->query($sql);
			$stmt->execute();
		
			//iterating through the rows
			$apps = array();
			$parent = -1;
			while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
				if(!isset($this->auth->groups[$row['grpid']]))
					$this->auth->groups[$row['grpid']] = new \model\core\Group();
				$this->auth->groups[$row['grpid']]->id = $row['grpid'];
				//if we discover a new app, add it to the app array
				$this->addApp($row['appid'], $row['appname']);
				//add this group to the app
				$this->addGroupToApp($row['appid'], $row['grpid']);
				//add app to group
				$this->addAppToGroup($row['appid'], $row['grpid']);
				//metainfo
				$this->addMetaInfo($row['grpid'], $row['name'], $row['value']);
				//add this as children to parent
				$this->addChild($grp, $row['grpid']);
				//add the parent for the group
				if(!isset($this->auth->groups[$row['grpid']]->parent))
					$this->auth->groups[$row['grpid']]->parent = $grp;
				//set the treeid
				if(is_null($this->auth->groups[$row['grpid']]->treeID))
					$this->auth->groups[$row['grpid']]->treeID = $row['treeid'];
				//mark that the grp is fetched
				$this->auth->groups[$row['grpid']]->isFetched = false;
				
				//note whether the app is setup
				if(!is_null($row['appid'])){
					if(is_null($row['is_setup']))
						$this->auth->apps[$row['appid']]->isSetup = false;
					else
						$this->auth->apps[$row['appid']]->isSetup = $row['is_setup'];
					
					$this->auth->apps[$row['appid']]->requireSetup = $row['require_setup'];
				}
				$this->setSorting($row['appid'], $row['sorting']);
			}
		}
		//if there is children, run through
		if(isset($this->auth->groups[$grp]->children))
			foreach($this->auth->groups[$grp]->children as $child){
				$this->applyInfo($child, 
					$this->auth->groups[$grp]->permissions,
					$this->auth->groups[$grp]->apps,
					$this->auth->groups[$grp]->members,
					false);
			}
	}
	
	/**
	* moved to a util?
	*/
	private function genSecureKey(){
		$str = time();
		$str .= rand();
		$str .= uniqid();
		$key = hash("sha256", $str);
		return $key;
	}
	
	/**
	* moved to a util?
	*/
	private function hashCleartext($cleartext){
		$str = $cleartext.'Salt';
		$str .= hash("sha512", $str).'salt';
		$str .= hash("sha512", $str).'salt';
		$str = hash("sha512", $str);
		return $str;
	}
	
	private function getDB(){
		if(isset($this->db))
			return $this->db;
		else{
			$this->db = new db(\config\config::$coreConfig);
			return $this->db;
		}
	}
	
	/**** auxillery methods, used when building auth structure! ****/
	
	/**
	* set sorting for an app
	*/
	public function setSorting($appID, $sorting){
		if(!is_null($appID))
			$this->auth->apps[$appID]->sorting = $sorting;
	}
	
	public function applySorting(){
		//sorting
		$func = function($a, $b){
			if ($a->sorting == $b->sorting)
				return 0;
			return ($a->sorting < $b->sorting) ? -1 : 1;
		};
		
		uasort($this->auth->apps, $func);
		uasort($this->auth->appnames, $func);
	}
	
	/**
	* add a permission to a group
	*
	* @param	grp			the group to add permission to (int)
	* @param	permission	the actual permission (int)
	*/
	private function addPermission($grp, $permission){
		if(	!isset($this->auth->groups[$grp]->permissions) || 
			!in_array($permission, $this->auth->groups[$grp]->permissions))
			$this->auth->groups[$grp]->permissions[] = $permission;
	}
	
	/**
	* adds a new app to the array
	*/
	private function addApp($appID, $appName){
		//check if the app is set
		if(isset($this->auth->apps[$appID]) || is_null($appName) || is_null($appID))
			return;
		
		//set some variable
		$this->auth->apps[$appID] = new \model\core\App();
		$this->auth->apps[$appID]->id = $appID;
		$this->auth->apps[$appID]->name = $appName;
		$this->auth->appnames[$appName] = &$this->auth->apps[$appID];
	}
	
	/**
	* adds a group to the given app
	*/
	private function addGroupToApp($appID, $groupID){
		if(	is_null($appID) ||
			(isset($this->auth->apps[$appID]->groups) && 
			in_array($groupID, $this->auth->apps[$appID]->groups)))
			return;
		$this->auth->apps[$appID]->groups[] = $groupID;
	}
	
	/**
	* adds app to group
	*/
	private function addAppToGroup($appID, $groupID){
		if(	is_null($appID) ||
			isset($this->auth->groups[$groupID]->apps) &&
			in_array($appID, $this->auth->groups[$groupID]->apps))
			return;
		$this->auth->groups[$groupID]->apps[] = $appID;
	}
	
	/**
	* add a children to group
	*/
	private function addChild($grp, $child){
		if($grp == $child)
			return;
		if($this->auth->groups[$grp]->children && in_array($child, $this->auth->groups[$grp]->children))
			return;
		$this->auth->groups[$grp]->children[] = $child;
	}
	
	/**
	* adds metainformation
	*/
	private function addMetaInfo($grp, $key, $value){
		if(is_null($key))
			return;
		$this->auth->groups[$grp]->metaInfo[$key] = $value;
	}
}

?>
