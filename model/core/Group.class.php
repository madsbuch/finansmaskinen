<?php

namespace model\core;

class Group extends \model\AbstractModel{
	
	/**
	* the group id
	*/
	protected $id;
	
	/**
	* the tree this group belongs to
	*/
	protected $treeID;
	
	/**
	* array of permissions to the group
	*/
	public $permissions;
	
	/**
	* accosiative array of info about this group
	*/
	public $metaInfo;
	
	/**
	* array of children
	*/
	public $children;
	
	/**
	* parent id
	*/
	protected $parent;
	
	/**
	* array of apps
	*/
	public $apps;
	
	/**
	* array of members (personal groups)
	*/
	protected $members;
	
	/**
	* whether this is fetched, used internally
	*/
	protected $isFetched = false;
}

?>
