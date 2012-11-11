<?php
/**
 * @author Mads Buch
 */

namespace helper\transform;

class Model extends \helper\transform implements ModelType{
	
	private $model;
	
	function setData($data){
		$this->model = $data;
	}
	
	function chain($obj){
		//check if it's an implementation of model, to be sure on the output
		if($obj instanceof Model)
			$this->model = $obj->getModel();
		else
			$this->model = $obj->generate();
	}
	
	function generate(){
		return $this->model;
	}
	
	function takeArguments(){
	
	}
	
	function getModel(){
		return $this->model;
	}
}

?>
