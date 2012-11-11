<?php
/**
* Handling notifications
*
*
*/

namespace core;
class notifications{
	
	/**
	* database handle holder
	*/	
	private $db;
	
	/**
	* yeah, constructor is nessesary, because of db
	*/
	function __construct(){
		$this->db = db::getInstance(\config\config::$coreConfig);
	}
	
	/**
	* insert notification
	*
	* if app is not set, it's a notification to the group.
	*/
	public function add($notification, $url, $grp, $app = -1){
		$toInsert = array(
			'app_id' => $app,
			'grp_id' => $grp,
			'notification' => $notification,
			'url' => $url,
			'timestamp' => time()
		);
		$this->db->insert($toInsert, 'notifications');
	}
	
	/**
	* get notifications
	*
	* @param limit	how many entries returned? default, all.
	* @param app	id of app, if -1, all apps (permissions is applied)
	* @param app	id of grp, if -1, all grps (permissions is applied)
	*/
	public function get($limit=-1, $app=-1, $grp=array()){
		//@TODO permissions
		$where = array(
			array('app_id', $app),
			array('grp_id', $grp),
			'OR',
			array('grp_id', '-1')//make sure we get the broadcast messages
		);
		return $this->db->getList('notifications', $where, 'timestamp', $order = false);
	}
	
	/**
	* adds a user message
	*
	* some times it can be usefull to set a messagebox for small messages
	* use this. All apps should check if this is present
	*/
	static public function addMsg($msg){
	
	}
	
	/**
	* clear message box
	*
	* if the messages has been shown, we may clear the message box
	*/
	static public function clearMsgBox(){
	
	}
}

?>
