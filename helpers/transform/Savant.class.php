<?php
/**
 * does transformation based on the savant system
 * this cannot be used initially, as it requires arguments to work properly
 * chaining as follows:
 *
 * model -> various
 *
 * for building templates:
 *  inside of the template, the only variable there is to use is the model, that
 *  contains the model passed.
 *
 * @author Mads Buch
 */

namespace helper\transform;

include PLUGINDIR.'Savant3/Savant3.php';

//include savant stuff there

class Savant extends \helper\transform{
	
	private $model;
	private $path;
	
	/**
	 * assumes input data is a model
	 */
	function setData($data){
		$this->model = $data;
	}
	
	static function create($data){
		//this processor requires arguments, and can therefor not be used as
		//chain initializer
		throw new \Exception('What about No?');
	}
	
	
	function chain($obj){
		//check if it's an implementation of model, to be sure on the output
		if($obj instanceof Model)
			$this->model = $obj->getModel();
		else
			$this->model = $obj->generate();
	}
	
	/**
	 * generates ouput of this class
	 */
	function generate(){
		$savant = new \Savant3();
		
		//add path to savant conf, fucking savant...
		$p = explode('/', $this->path);
		$file='';
		$file = array_pop($p);
		$p = implode('/', $p);
		$savant->addPath('template', $p.'/');
		
		$savant->model = $this->model;
		return $savant->fetch($file);
	}
	
	function takeArguments(){
		//must comply with abstract method, so this seams to be the way doing it ;)
		$args = func_get_args();
		if(!isset($args[0]))
			throw new \exception('no template provided');
		$this->path = $args[0];
	}
}

?>
