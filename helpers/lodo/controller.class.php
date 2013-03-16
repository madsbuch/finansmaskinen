<?php
/**
 * lodo - list of dataobjects
 *
 * this helper provides a list of objects. each object can be in multiple groups
 * but only one list pr. group. this may be usefull for 'tagging' objects, so
 * partial access to toher users are possible
 *
 *
 * database layout should be as follows:
 *
 * {development phase}
 *
 * type rules: even numbers: small data table, odd numbers: large data table
 * @TODO add namespaces! Det giver mulighed for at et app, kan lave nogle
 * obligatoriske felter.
 */

namespace helper;

include_once('obj.class.php');

class lodo
{

	/**
	 * mongo DB object
	 */
	private $DB;

	/**
	 * and the collection
	 */
	private $collection;

	/**
	 * the conditions
	 */
	private $conditions;

	/**
	 * the order of the results
	 */
	private $order;

	/**
	 * the limit
	 */
	private $limit;

	/**
	 * the fields to return from the query
	 */
	private $fields = array();

	/**
	 * helper_core holder
	 */
	private $core;

	/**
	 * keys for fulltext index at insertion
	 */
	private $fulltextKeys = array();

	/**
	 * if objects are retrived from here, they come wrapped in this
	 */
	private $c = '\model\Base';

	/**
	 * array containing all groups open for this app
	 */
	private $grpArr;

	/**
	 * whether just to return a cursor
	 */
	private $returnCursor = false;

	/**
	 * walk through and use og cildren
	 *
	 * NOT YET IMPLEMENTED
	 */
	//private $recursive = false;

	/**
	 * construct
	 *
	 * @param $collection:    string    the collection to fetch data from
	 * @param $app            the requesting app. for fetchin groups
	 * @param $treeID:        treeid, only groups within this tree are used. if not set, default is used.
	 */
	function __construct($collection, $app, $treeID = false)
	{
		//set some private variables
		$this->collection = $collection;

		//construct a class witch information
		$this->core = new \helper\core($app);

		//fetch grp info
		$grp = $this->core->getGrp($app);

		//assigninging the array
		$this->setGroups($grp);

		//setting DB connection
		$this->DB = $this->core->getDB('mongo');

		$this->collection = $this->DB->getCollection($collection);

		$this->setGroupCondition();
		$this->setFields(array(
			'_subsystem.fulltext_index' => false,
			'_subsystem.edited_by' => false,
		));

		$this->sort(array('_subsystem.updated_at' => -1));
	}

	//region setters

	function setGroupCondition()
	{
		//intify, shoud be done down low
		foreach ($this->grpArr as &$g) {
			$g = (int)$g;
		}
		$this->conditions['_subsystem.groups'] = array('$in' => $this->grpArr);
	}

	/**
	 * search
	 *
	 * adds a find to the mongo cursor
	 */
	public function addCondition($cond)
	{
		$this->conditions = array_merge($cond, $this->conditions);
	}

	/**
	 * adds a condition for fulltext search. that insures fulltext search
	 * in earlier specified indexes
	 */
	function addFulltextSearch($value)
	{
		//they were saved with attention to spaces, they will be retrived so
		$expl = explode(' ', mb_strtolower(urldecode($value)));
		$cond = array();
		foreach ($expl as $e) {
			$cond[] = new \MongoRegex('/^' . $e . '/u');
		}
		$this->addCondition(array('_subsystem.fulltext_index' => array('$all' => $cond)));
	}

	/**
	 * set ordering of result
	 *
	 * see php mongo docs for details
	 */
	function setOrder($order)
	{
		$this->order = $order;
	}

	function sort($order)
	{
		$this->setOrder($order);
	}

	/**
	 * set limit of the result
	 *
	 * see php mongo docs for details
	 */
	function setLimit($limit)
	{
		$this->limit = $limit;
	}

	function limit($l)
	{
		$this->setLimit($l);
	}

	/**
	 * add the fields to return
	 */
	function setFields($fields)
	{
		$this->fields = $fields;
	}

	/**
	 * explicitly specify group.
	 *
	 * THIS OVERWRITES THE GROUPS ALLREADY SPECIFIED
	 *
	 * as default, all groups that are accesible are applied, but not all might
	 * this is also used as tags
	 *
	 * @param $grp array of grps to apply
	 * @throws \Exception
	 * @return bool
	 */
	function setGroups($grp)
	{
		if (!is_array($grp))
			throw new \Exception('groups has to be an array');
		//make sure we use int
		$arr = array();
		foreach ($grp as $g)
			$arr[] = (int)$g;
		$this->grpArr = $arr;
		return true;
	}

	/**
	 * classes to return
	 */
	function setReturnType($class)
	{
		$this->c = $class;
	}

	//endregion

	/**
	 * returns a cursor over the conditions
	 */
	function getCursor()
	{
		$c = $this->collection->find($this->conditions, $this->fields);
		$c = $c->limit($this->limit);
		$c = $c->sort($this->order);
		return $c;
	}

	/**
	 * links documents from other collection
	 *
	 * @param $collection
	 * @param $source
	 * @param $target
	 */
	function link($collection, $source, $target){

	}

    /**
     * inserts an object, and returns it populated
     *
     * @param $obj
     * @return mixed
     * @throws Exception
     */
    function insert($obj)
	{
		//@TODO check permissions

		//for later use, make sure we return file of same type as we recieve
		$c = get_class($obj);

		$obj = $this->getArray($obj);
		//validation
		if (isset($obj['_subsystem']))
			throw new Exception('"_subsystem" is a reserved veriable');

		$obj['_subsystem'] = $this->doSubsystem($obj);

		$this->collection->insert($obj, array('safe' => true));

		return new $c($obj);
	}

    /**
     * marks a single object as deleted
     *
     * @param $id
     */
    function delete($id)
	{
		//update the document
		$this->collection->update(array('_id' => new \MongoId($id), array('$set' => array('_subsystem.deleted' => true))));
	}

	/**
	 * takes keys to add to fulltext search index
	 *
	 * delimiter between subarrays are .'s
	 */
	function setFulltextIndex($keys)
	{
		$this->fulltextKeys = $keys;
	}

	/**
	 * saves object
	 *
	 * creates it, if it doesn't excists
	 */
	function save($obj)
	{
		//@TODO check permissions

		//for later use, make sure we return file of same type as we recieve
		if (is_object($obj))
			$c = get_class($obj);



		$obj = $this->getArray($obj);

		//validation
		if (!isset($obj['_subsystem'])) {
			$obj['_subsystem']['created_at'] = time();
			$obj['_subsystem']['groups'] = $this->grpArr;
		}
		$t = isset($obj['_subsystem']['created_at']) ? $obj['_subsystem']['created_at'] : time();
		$obj['_subsystem'] = $this->doSubsystem($obj);
		$obj['_subsystem']['created_at'] = $t;

		$this->collection->save($obj, array('safe' => true));

		if (isset($c))
			return new $c($obj);
		return $obj;
	}

	/**
	 * updates an document, and saves it to the database.
	 *
	 * @param $obj
	 * @throws \exception\UserException
	 * @throws \Exception
	 * @return mixed
	 */
    function update($obj)
	{
		//@TODO check permissions

		$obj = $this->getArray($obj);
		if (!$obj['_id'])
			throw new \Exception('No id set on the object');



		$this->conditions['_id'] = new \MongoID($obj['_id']);

		$old = $this->findOne();

		if(empty($old))
			throw new \exception\UserException('Nothing to update');

		$old->merge($obj, true);
		$new = $old->toArray();


		$new['_subsystem'] = $this->doSubsystem($new);

		unset($new['_id']);

		return $this->collection->update($this->conditions, $new, array('safe' => true));
	}

	/**
	 * pushes object to field directly
	 *
	 * @param $field
	 * @param $object
	 */
	function push($field, $object){
		$this->collection->update($this->conditions, array('$push' => array($field => $this->getArray($object))));
	}

	/**
	 * @throws \Exception
	 */
	function lock()
	{
		throw new \Exception('Not implemented');
	}

	/**
	 * @throws \Exception
	 */
	function unlock()
	{
		throw new \Exception('Not implemented');
	}

	/**
	 * transperant compared to the mongo one
	 *
	 * wraps it in the object set
     * @return \model\AbstractModel
	 */
	function findOne()
	{
		$this->setGroupCondition();
		$cursor = $this->getCursor();
		$obj = new $this->c($cursor->getNext());

		return $obj;
	}

	function returnCursor()
	{
		$this->returnCursor = true;
	}

	/**
	 * returns array of objects
	 *
	 * @param null $type
	 * @return array
	 */
	public function getObjects($type = null)
	{
		$grps = $this->getAuthorizedGroups(\config\constants::READ);

		$this->grps = $grps;
		$this->setGroupCondition();

		//no objects
		if (count($grps) < 1)
			return array();

		$cursor = $this->getCursor();

		if ($this->returnCursor)
			return $cursor;

		//return empty set, if no elements
		if (!$cursor->hasNext())
			return array();

		$type = $type ? $type : $this->c;

		foreach ($cursor as $element) {
			if ($type) {
				$ret[] = new $type($element);
			} else
				$ret[] = $element;
		}

		if (!isset($ret))
			return array();
		return $ret;
	}

	/**
	 * return a single object based on id
	 */
	public function getFromId($id)
	{
		$conditions = $this->conditions;
		$conditions['_id'] = new \MongoID($id);
		if (($res = $this->collection->findOne($conditions)) === null)
			return null;
		return new $this->c($res);
	}

	/**
	 * optimizes collection
	 *
	 * additional indexes is defined by $index
	 */
	public function optimizeCollection($index)
	{
		$index = array_merge(array(
			'_subsystem.updated_at',
			'_subsystem.created_at',
			'_subsystem.fulltext_index',
		), $index);
	}

	/******************************** AUX *************************************/

	/**
	 * prepares the subsystem for insertion
	 *
	 * if allready created, it is updated.
	 *
	 * invariant: object is an assiciative array
	 */
	function doSubsystem($obj)
	{
		$ret = array();
		$ret['updated_at'] = time();

		if (!isset($obj['_subsystem']['created_at']))
			$ret['created_at'] = time();
		else
			$ret['created_at'] = $obj['_subsystem']['created_at'];

		$ret['groups'] = $this->grpArr;
		$ret['edited_by'] = null;

		// create some fulltext index
		$strings = array();
		foreach ($this->fulltextKeys as $key) {
			//explode it, we only wants single words
			$expl = explode(' ', mb_strtolower((string)array_recurse_value($key, $obj)));
			$strings = array_merge($strings, $expl);
		}
		$ret['fulltext_index'] = $this->buildSuffixTrie($strings);
		return $ret;
	}

	/**
	 * updates subsystem
	 */
	function updateSubsystem($obj)
	{
		/*	$ret = array();
			$ret['updated_at'] = time();
			$ret['groups'] = $this->grpArr;

			// create some fulltext index
			$strings = array();
			foreach($this->fulltextKeys as $key){
				//explode it, we only wants single words
				$expl = explode(' ', mb_strtolower((string) array_recurse_value($key, $obj)));
				$strings = array_merge($strings, $expl);
			}
			$ret['fulltext_index'] = $this->buildSuffixTrie($strings);
			return $ret;not done **/
	}

	/**
	 * takes array of strings, and retunrs an array, of suffix strings
	 *
	 * @param $strings array array of string to create suffix over
	 * @return array the trie dumped as strings (one for every endleaf in the tree)
	 */
	private function buildSuffixTrie($strings)
	{
		//build the trie
		$trie = array();
		foreach ($strings as $s) {
			while (strlen($s) > 0) { //take all suffixes
				$suffix = $s;
				$s = mb_substr($s, 1);

				//add the suffix tot the trie
				$p = & $trie;
				while (strlen($suffix) > 0) {
					//shorten the string
					$t = mb_substr($suffix, 0, 1);
					$suffix = mb_substr($suffix, 1);

					if (!isset($p[$t]))
						$p[$t] = array();
					$p = & $p[$t];
				}
			}
		}
		//return the dumped trie
		return $this->dumpTrie($trie);
	}

	/**
	 * dumps trie to array
	 */
	private function dumpTrie($t)
	{
		$ret = array();

		//takes all the key of the array, and appends dumps of values to them
		foreach ($t as $c => $trie) {
			$dumps = $this->dumpTrie($trie);
			if (!empty($dumps))
				foreach ($dumps as $dumped) {
					$ret[] = $c . $dumped;
				}
			//if no value (aka, empty string), we'll just append the character
			else
				$ret[] = (string)$c;
		}
		return $ret;
	}

	/**
	 * converts structure of objects to associated arrays instead
	 */
	private function getArray($object)
	{
		if (method_exists($object, 'toArray')) {
			return $object->toArray();
		}
		return (array)$object;
	}

	/**
	 * get array of groups, by the field ones, that are authorized to this operation
	 */
	private function getAuthorizedGroups($perm)
	{
		if (!$this->grpArr)
			return array();
		$toWriteTo = array();
		foreach ($this->grpArr as $g) {
			if ($this->core->isAllowed($g, $perm))
				$toWriteTo[] = $g;
		}
		return $toWriteTo;
	}
}
