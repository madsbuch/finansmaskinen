<?php

namespace model\core;

class Auth extends \model\AbstractModel{
	
	/**
	* whether to update the array (if changes for sure are made)
	*/
	protected $update = false;
	
	/**
	* userID of user
	*/
	protected $userID;
	
	/**
	* the main group, that holds the data for this user
	*/
	protected $mainGroup;
	
	/**
	* list of allavailable tree's, excluding personal group
	*/
	public $trees;
	
	/**
	* current tree
	*/
	protected $treeID;
	
	/**
	* array of \model\core\App objects
	*/
	public $apps;
	
	/**
	* this is an alias for $apps using appname as array index, instead
	* of the id
	*/
	public $appnames;
	
	/**
	* groups in the current tree
	*
	* needs to be public as it is used is an array
	*/
	public $groups;
	
	/**
	* auxGroups
	*
	* holder for groups that are not in the tree, but applies to the struct
	*
	* this is f.eks. common groups and possible groups from other trees
	*/
	public $auxGroups;
}

?>
