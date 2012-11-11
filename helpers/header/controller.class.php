<?php
/**
* Denne klasse sørger for at generere proper http header for indholdet.
*/

namespace helper;

class header{
	
	//holder for the header
	private $header;
	
	function __construct(){
	
	}

	/**
	* Redirect
	*
	* remember to stop execution after issuing this method:
	*
	* core_header::redirect(...)
	* return;
	*/
	function redirect($url){
		$this->header["Location:"] = $url;
	}
	
	public function generate(){
		$str = "";
		if(empty($this->header))
			return;
		foreach($this->header as $name => $value)
			$str = "$name $value\n";
		return $str;
	}
	
	/**
	* set mime type
	*
	* if you want to force download, let type=download, and filename= <nameOfFile>
	*/
	function setMime($type, $filename="download"){
		$type = \strtolower($type);
		switch($type){
			case "css":
				$this->header['Content-type:'] = 'text/css; charset=utf-8';
			break;
			case "jpg":
			case "jpeg":
				$this->header['Content-type:'] = 'image/jpg';
			break;
			case "png":
				$this->header['Content-type:'] = 'image/png';
			break;
			case "gif":
				$this->header['Content-type:'] = 'image/gif';
			break;
			case "htm":
			case "html":
				$this->header['Content-type:'] = 'text/html; charset=utf-8';
			break;
			case "pdf":
				$this->header["Content-Type:"] = "application/pdf";
			break;
			case "js":
			case "javascript":
				$this->header['Content-type:'] = 'application/x-javascript; charset=utf-8';
			break;
			case 'json':
				$this->header["Content-Type:"] = "application/json";
			break;
			case "download":
			break;
			default:
				$this->header["Content-Type:"] = $type;
			break;
		}
		return true;
	}
	
	/**
	* set response code
	*/
	function setResponse($code){
		$this->header["HTTP/1.0 404 Not Found"] = '';
	}
	
	function download($filename){
		$this->header["Content-Disposition:"] = 'attachment; filename='.$filename;
	}
	
	public function getHeader(){
		return $this->generate();
	}
}

?>
