<?php
/**
* input parser
*
* This class handles all input. It also takes care of routing from the router
* file.
*/
namespace core;
class inputParser{
	/*********** FOR SINGLETON ***********/
	// Hold an instance of the class
	private static $instance;

	// The singleton method
	public static function getInstance(){
		if(!isset(self::$instance)){
			$c = __CLASS__;
			self::$instance = new $c;
		}

		return self::$instance;
	}

	// Prevent users to clone the instance
	public function __clone(){
	  trigger_error('Clone is not allowed.', E_USER_ERROR);
	}
	
	/**
	* arrays with holds the URI and the domains (exploded by / and .)
	*/
	private $URI;//uri arr (by /)
	private $domain;//domain arr (by .)
	
	/**
	* lazy populated
	*/
	private $reqBody;
	
	/********* THE CLASS ***************/
	private function __construct(){
		global $_POST, $_GET;
		//remove the get data:
		$this->post = $_POST;
		$this->get = $_GET;
		
		//make sure nobody can read data directly from the variables
		$_POST = "";
		unset($_POST);
		$_GET = "";
		unset($_GET);
		
		//parse get data
		$realData = explode("?", $this->getURI());
		$this->URI = explode("/", $realData[0]);
		$this->domain = explode(".", $this->getDomain());
	}
	/**
	* returns URI
	*/
	public function getURI(){
		return $_SERVER["REQUEST_URI"];
	}
	
	/**
	* Returns the domain (vhost)
	*/
	public function getDomain(){
		return $_SERVER['SERVER_NAME'];
	}
	
	public function getURL(){
		return $this->getDomain().$this->getURI();
	}
	
	/**
	* this function reads the request body, and makes sure to save it, for evt
	* later retrieval.
	*/
	public function getRequestBody(){
		if(is_null($this->reqBody))
			$this->reqBody = file_get_contents('php://input');
		return $this->reqBody;
	}
	
	/**
	* get header from key
	*/
	function getRequestHeader($key){
		//return $_SERVER['CONTENT_TYPE'];
	}
	
	/**
	* variables:
	*/
	
	private $post;//post data
	private $get;//get data
	
	
	/*************************** URL PARSING **********************************/
	
	/**
	* returns current site (or profile if you want)
	*
	* returns tld independent site str.
	* ex: appf (from finansmaskinen.dev -> finance)
	* this function takes care to router.php
	*/
	function getSite(){
		$site = $this->getReverseDomain();
		$domain = $site[1].".".$site[0];
		return \config\router::$domains[$domain];
	}
	
	/**
	* Alias for getSite();
	*/
	function getProfile(){
		return $this->getSite();
	}
	
	/**
	* returns requested filetype, or null if none
	*/
	function getFileType(){
		$realData = explode("?", $this->getURI());
		$type = explode('.', $realData[0]);
		return count($type) > 1 ? $type[1] : null;
	}
	
	/**
	* getStartDir
	*
	* return startDir, directory of profile
	*/
	function getStartDir(){
		$profile = $this->getSite();
		return ROOT."start/".\config\router::$profiles[$profile]['start'];
	}
	
	/**
	* returns name name of the app
	*/
	function getApp(){
		if(!empty($this->URI[1]))
			if($this->URI[1] == 'index')
				return'main';
			else
				return $this->URI[1];
		return "main";
	}
	
	/**
	* returns name of current page
	*/
	function getPage(){
		if(!empty($this->URI[2])){
			$u = explode('.', $this->URI[2]);
			return $u[0];
		}
		return "index";
	}
	
	/**
	* get arguments
	*
	* return an array of arguments given in URI
	*/
	function getArgs($num = -1){
		$arg = array_slice($this->URI, 3);
		
		if(isset($arg[$num]))
			return $arg[$num];
		return $arg;
	}
	
	/**
	* returns array conaining domainin reverse
	*
	* ex: array(dev, appf, static). mostly used for tests on domain (subdomains)
	*/
	function getReverseDomain(){
		return array_reverse($this->domain);
	}
	
	/************************** POST INFORMATION PARSING **********************/
	
	/**
	* returns array containing files sent to server, empty if none
	*
	*/
	function getFiles(){
		return $_FILES;
	}
	
	/**
	* getPost
	*
	* returning post array
	*/
	function getPost(){
		return $this->post;
	}
	
	/******************** PARAMETER (GET) INFORMATION PARSING *****************/
	
	/**
	* get parameter
	*
	* returns url-string parameters
	*/
	function getParameter($param){
		if(isset($this->get[$param]))
			return $this->get[$param];
		return;
	}
	
	function getParameters(){
		return $this->get;
	}
	
	/**
	* getGET
	*
	* alias of getParameter
	*/
	function getGET($param){
		return $this->getParameter($param);
	}
	
	/************************** LANGUAGE PARSING ******************************/
	
	function getLan(){
	
	}
}

?>
