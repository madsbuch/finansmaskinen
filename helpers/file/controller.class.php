<?php
/**
* file abstracting, for saving and retrieving file
*
* a file, like other resources, are associated with groups
*/


namespace helper;

class file{
	
	private $core;
	private $grp;
	private $grid;
	
	/**
	* time to live without approval, 20 hours
	*/
	public $ttl = 72000;
	
	function __construct($app, $collection='files'){
		$this->core = new \helper\core($app);
		$this->grp = $this->core->getGrp();
		$this->grid = $this->core->getDB('mongo')->database->getGridFS($collection);
	}
	
	/**
	* returns id of file
	*
	* path: whether $file is the actual file, or a path to it.
	* details, 
	*/
	function saveFile($file, \model\platform\File $details = null, $path = true){
		var_dump($file);
		
		if(empty($file))
			return null;
		
		$name = '';
		/**
		* calculate the filename
		* 
		* this allows us to merge identical files together, saving some space
		*/
		if($path)
			$sha1 = sha1_file($file);
		else
			$sha1 = sha1($file);
			
		//some details processing
		$details = $details->toArray();
		//sanitize details
		unset($details['file']);
		$details['_sha1'] = $sha1;
		
		//some options
		$options['safe'] = true;
		if($path){
			$ret = $this->grid->storeFile(
				$file,
				array("metadata" => $details),
				$options);
			var_dump($ret);
		}
		else
			$ret = $this->grid->storeBytes(
				$name,
				array("metadata" => $details),
				$options);
		
		$details['_id'] = (string) $ret;
		return new \model\platform\File($details);
	}
	
	/**
	* takes the $_FORM and processes that.
	*
	* the function extracts all files, and returns a liste of objects lige
	* saveFile
	*/
	function fromPost($post){
		$ret = array();
		foreach($post as $p){
			//add all elements
			if(is_array($p['name'])){
				foreach($p['name'] as $file)
					$ret[] = $this->saveFile($file);
			}
			else{
				//some metadata
				$meta = new \model\platform\File;
				$meta->name = $p['name'];
				$meta->groups = $this->grp;
				$meta->mime = $p['type'];
				
				$ret[] = $this->saveFile($p['tmp_name'], $meta, true);
				
			}
		}
		
		return $ret;
	}
	
	/**
	* takes ID of file, and returns a file object
	*/
	function getFile($file){
		$file = $this->grid->findOne(array('_id' => new \MongoID($file)));
		if(!$file)//no file found
			return null;
		
		//@TODO permissions here
		
		$obj = new \model\platform\File( $file->file['metadata'] );
		$obj->_id = (string) $file->file['_id'];
		
		$file =  new file\File($file, file\File::MONGO);
		
		$obj->file = $file;
		
		return $obj;
	}
	
	/**
	* If a file is approved, it is deleted after the ttl is expired
	*
	* this is due to all the ajax upload, where users might upload some data,
	* without saving the form, we don't wanna hold that data
	*/
	function approve($file){
	
	}
	
	/**
	* this function is sometimes ran.
	*
	* that is done by the subsystem
	*/
	public static function cron(){
	
	}
	
}
?>
