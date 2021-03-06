<?php
/**
 * @author Mads Buch
 *
 * works as follows:
 *
 * html -> cacheentry
 */

namespace helper\transform;

//include savant stuff there

class PDF extends \helper\transform{
	
	private $data;
	private $mode;
	
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
		$this->data = $obj->generate();
	}
	
	/**
	 * generates ouput of this class
	 */
	function generate(){
		//so, the only mode supported for now is HTML
		
		//generate unique filename
		$fn = uniqid(crc32($this->data), true);
		
		//save data to file, so it can be read by the plugin
		file_put_contents(__DIR__.'/tmp/'.$fn.'.html', $this->data);
		chmod(__DIR__.'/tmp/'.$fn.'.html', 0777);
		
		//convert the saved data
		if(!\wkhtmltox_convert('pdf', 
		    	array(
		    		'out' => __DIR__.'/tmp/'.$fn.'.pdf',
		    		'imageQuality' => '100',
		    		),
		    	array(
		    		array(
		    			'page' => __DIR__.'/tmp/'.$fn.'.html',
		    			'web.printMediaType' => true
		    		),
		    	)))
			throw new \Exception('Couldn\'t do conversion');
		chmod(__DIR__.'/tmp/'.$fn.'.pdf', 0777);
		
		//reads filecontent
		$ret = file_get_contents(__DIR__.'/tmp/'.$fn.'.pdf');
		
		//perform cleanup
		unlink(__DIR__.'/tmp/'.$fn.'.pdf');
		unlink(__DIR__.'/tmp/'.$fn.'.html');
		
		//return the data
		return $ret;
	}
	
	/**
	 * read arguments to processor
	 *
	 * ($mode, $asFile=false)
	 *
	 * @TODO passes a cacheobject and a identifier of the content instead of raw
	 * data
	 */
	function takeArguments(){
		//must comply with abstract method, so this seams to be the way doing it ;)
		$args = func_get_args();
		if(!isset($args[0]))
			throw new \exception('no mode provided');
		$this->mode = $args[0];
	}
}

?>
