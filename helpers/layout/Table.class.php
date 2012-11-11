<?php
/**
* create a std list based on a collection of objects, and a descreptive config
*/

namespace helper\layout;

class Table extends \helper\layout\LayoutBlock{
	
	/**** some public properties ****/
	public $showHeader = true;
	
	protected $attr;
	protected $collection;
	protected $nullVal = '';
	protected $emptyVal = '';
	protected $dom;
	protected $addClasses = '';
	
	private $ajaxSource;
	
	/**
	* $attr = array(
	* 	'selector'  => Header
	*	'selector2' => array(Header, function($data[, $dom], td, tr, add...)
	*		{return $data}, $additional paramters (array))
	* )
	*
	* for selectors:
	* a dot '.' return the object in the end, a selector like '.' will provide
	* the function with all of the object, 'Item.' will provide everything in
	* the end of Item
	*/
	function __construct($attr){
		$this->attr = $attr;
		$this->dom = new \DOMDocument();
	}
	
	/**
	* takes a lodo object and returns sorted json
	* this also applies formatting function applied
	* setIterator needs ta have the lodo
	*
	* @TODO fix it so the ufuction uses $this->attr
	*
	* @param $parameters http parameters 
	*/
	function generateJson($param, $linkPrefix = ''){
		//format input
		$start = (int) $param['iDisplayStart'];
		$limit = (int) $param['iDisplayLength'];
		
		$cols = array();
		foreach($this->attr as $attr => $v){
			$cols[] = $attr;
		}
		
		$sortCol = (int) $param['iSortCol_0'];
		$direction = 1;
		if($param['sSortDir_0'] != 'asc')
			$direction = -1;
		$sort = array($cols[$sortCol] => $direction);
		
		//applying everything
		if(strlen($param['sSearch']) > 0)
			$this->collection->addFulltextSearch((string) $param['sSearch']);
		$this->collection->sort($sort);
		$this->collection->limit($limit);
		$this->collection->returnCursor();
		
		//do output
		$iterator = $this->collection->getObjects('\model\finance\Contact');
		$total = $iterator->count(false);
		$iterator->skip($start);
		
		$count = 0;
		$aaData = array();
		foreach($iterator as $entry){
			foreach($cols as $c){
				if(is_array($this->attr[$c]))
					$toAA[] = call_user_func_array($this->attr[$c][1], array(array_recurse_value($c, $entry)));
				else
					$toAA[] = array_recurse_value($c, $entry);
			}
			$toAA['DT_RowId'] = $linkPrefix . $entry['_id'];
			$aaData[] = $toAA;
			unset($toAA);
			$count++;
		}
		
		$ret = array(
			'sEcho' => $param['sEcho'],
			'iTotalRecords' => $total,
 			'iTotalDisplayRecords' => $total,
			'aaData' => $aaData);
		return json_encode($ret);
	}
	
	/**
	* add additional classes
	*/
	function additionalClasses($cls){
		$this->addClasses = $cls;
	}
	
	/**
	* enables ajax for this table
	*
	* the callback brings following parameters:
	* 	iDisplayStart, beginning offset of dataset
	*	iDisplayLength, number of requested items
	*	iSortCol_0 - colnum for ordering
	*
	*	sSearch, filtering, search
	*	aColumns_0 - colnum for columns search
	*/
	function useAjax($source){
		$this->ajaxSource = $source;
		$this->addCSSIncludes = array('/resources/dataTables/bs.css');
	}
	
	/**
	* add object to the table
	*/
	function addObject($obj){
		$this->collection[] = $obj;
	}
	
	/**
	* OR set iterator
	*
	* this unsets all objects added
	*/
	function setItterator($itt){
		$this->collection = &$itt;
	}
	function setIterator($d){//doh for spelling error :S
		return $this->setItterator($d);
	}
	
	/**
	* generate a list from objects pushed
	*/
	function generate(){
		$ret = $this->dom->createElement('table');
		
		$class = ($this->ajaxSource ? 'table ajaxTable ' : 'table ') . $this->addClasses;
		$ret->setAttribute('class', $class);
		
		$header = $this->dom->createElement('thead');
		$body = $this->dom->createElement('tbody');
		$ret->appendChild($header);
		$ret->appendChild($body);
		
		if($this->ajaxSource)
			$ret->setAttribute('data-ajaxSource', $this->ajaxSource);
		
		if($this->showHeader)
			$header->appendChild($this->createBlock('tr', 'th'));
		
		//var_dump($this->collection);
		if($this->collection){
			foreach($this->collection as $obj){
				$body->appendChild($this->createBlock('tr', 'td', $obj));
			}
		}
		elseif(!$this->ajaxSource){
			$div = $this->dom->createElement('div', $this->emptyVal);
			$div->setAttribute('class', 'alert alert-info');
			$ret = $div;
			
		}
			
		return $ret;
	}
	
	/**
	* set the null value
	*
	* so, if a value is null, this value is inserted instead
	*/
	public function setNull($s){
		$this->nullVal = $s;
	}
	
	public function setEmpty($s){
		$this->emptyVal = $s;
	}
	
	/**
	* create a row
	*/
	private function createBlock($r, $c, $obj=null){
	
		$ret = $this->dom->createElement($r);
		
		foreach($this->attr as $index => $colName){
			//if no object, the titles of the row is outputted
			if(is_null($obj)){
				if(is_array($colName))
					$ret->appendChild($this->dom->createElement($c, $colName[0]));
				else
					$ret->appendChild($this->dom->createElement($c, $colName));
			}
			//if there is a object to this line
			else{
				if(is_array($colName)){
					$toAdd = $this->dom->createElement($c);
					//a function is provided for this field. We provide: the content, dom structure, 
					//the row and the field, and some userdefined args
					if(!isset($colName[2]) || !is_array($colName[2]))
						$colName[2] = array();
					$args = array_merge(
						array($this->getValue($index,$obj), $this->dom, $toAdd, $ret),
						$colName[2]);
					$toAdd->appendChild(call_user_func_array($colName[1], $args));
					$ret->appendChild($toAdd);
				}
				else
					$ret->appendChild($this->dom->createElement($c, $this->getValue($index,$obj)));
			}
		}
		
		return $ret;
	}
	
	private function getValue($s, $a){
		if(($ret = array_recurse_value($s, $a)) !== null)
			return $ret;
		return $this->nullVal;
	}
}


?>
