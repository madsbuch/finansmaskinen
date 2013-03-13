<?
/**
* depends: core classes in general
*
* this class is rather important. no app developer has any knowledge on the
* core, så this is the only interface to the core.
*/
namespace helper;
class core{
	/**
	* app
	*
	* the app from which this class is called
	*/
	private $app;
	/**
	* for extracting DB info and so, this should be fine, but not for figuring
	* out wich app this is
	*/
	private $pageInfo;
	private $notifications;
	
	/**
	* instance of core\group
	*/
	private $grp;
	
	/**
	* should this be private? and why?
	*/
	function __construct($app){
		$this->app = $app;
		$this->pageInfo = \core\inputParser::getInstance();
		$this->grp = \core\groups::getInstance();
		$this->auth = \core\auth::getInstance();
		
		if($this->app){
			$this->apps = $this->auth->getApps();
			$this->appObj = $this->apps[$app];
		}
	}

	/**** INTERACTING WITH APPS ****/
	//region apps

	function callAll($functionName, $args=null){
		$site = $this->pageInfo->getSite();
		$site = 'start\\'.$site.'\api';
		$site = new $site;
		$apps = $site->appIterator();
		$ret = array();
		foreach($apps as $app){
			$callback = array('api\\'.$app->name, 'on_'.$functionName);
			if(method_exists($callback[0], $callback[1])){
				$r = null;
				if($args)
					$r = call_user_func_array($callback, $args);
				else
					$r = call_user_func($callback);

				//@TODO check type, if $returnType is set

				if(is_array($r))
					$ret = array_merge($ret, $r);
				else
					$ret[] = $r;
			}
		}
		return $ret;
	}

	//enregion

	/******************** ABSTRACTIONS FOR WORKING ON AUTH ********************/
	
	/**
	* call when the user is logges in, and some chagnes have been made
	*/
	function reFetch(){
		$this->auth->reFetch();
	}
	
	/******************** ABSTRACTIONS FOR WORKING ON GROUPS ******************/
	
	/**
	* return a list of all groups
	*
	* notive the static context
	*/
	static function getAllGroups(){
		return \core\groups::getInstance()->getGroups();
	}
	
	/**
	* return current group / groups
	*
	* @return	array(0 => id1, 1=>id2)
	*/
	function getGrp(){
		return $this->grp->getGrpForApp($this->app);
	}
	
	/**
	* returns group id of common group
	*/
	function getCommonGroup($grpString){
		return $this->grp->getCommonGroup($grpString);
	}
	
	/**
	* returns the main group for this app
	*/
	function getMainGroup(){
		foreach($this->grp->getGrpForApp($this->app, true) as $g){
			if(isset($g->metaInfo['mainFor']) && $g->metaInfo['mainFor'] == $this->appObj->id)
				return $g->id;
		}
		throw new \Exception('No main group');
	}
	
	function getTreeID(){
		return $this->grp->getTree();
	}
	
	/**
	* returns true, if gruop is authorized for app
	*/
	function grpAuthForApp($grp){
		$revArr = \array_flip($this->getGrp());
		if(isset($revArr[$grp]))
			return true;
		return false;
	}
	
	/**
	* get permissions for given group
	*/
	function isAllowed($grp, $permission){
		return $this->grp->getPermissions($grp, $permission);
	}
	
	/**
	* create new grp
	*
	* create a new group, eventually as a child of parentID
	*/
	public function createGroup($parentID=-1){
		//@TODO check permissions
		return $this->grp->createGroup((int) $parentID);
	}
	
	public function setMeta($group, $key, $val){
		//@TODO check permissions
		return $this->grp->setMeta($group, $key, $val);
	}
	
	/******************** ABSTRACTIONS FOR WORKING ON INPUT *******************/
	
	/**
	* get arguments from URI
	*
	* if no argument given, it returns the whole list as array
	*/
	function getURIArgs($num = -1){
		return $this->pageInfo->getArgs($num);
	}
	
	/**
	* get data from POST
	*/
	static function getPost(){
		return \core\inputParser::getInstance()->getPost();
	}
	
	/******************* NOTIFICATIONS*****************************************/
	
	function getNotifications($limit, $grp=array(), $app=-1){
		if(empty($this->notifications))
			$this->notifications = new \core\notifications();
			
		return $this->notifications->get($limit, $app, $grp);
	}
	
	function notify($msg){
	
	}
	
	/**
	* returns message from user
	*
	* THIS ALSO EMPTIES THE CONTENTS, SO REMEMBER TO WRITE IT OUT!!
	*/
	function getUsrMsg(){
		//@TODO empty yhe cache 
	}
	
	/**
	* add a message to the message cache
	*
	* the message cache is a session cache, that contains eventual messages
	* from the system to the user.
	*/
	function addUsrMsg($msg){
		
	}
	
	/************************** DATABASE **************************************/
	
	/**
	* returns a database object
	*/
	function getDB($type='mysql'){
		return \core\db::getInstance($this->pageInfo->getProfile(), $type);
	}
}

?>
