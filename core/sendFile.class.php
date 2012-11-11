<?php
/**
* serves static content
* 
* fetches and outputs static content
* For now, alle putput are controlled from within this class, also the headers
*
* @TODO build in cachecontrol, and continue download.
*/
namespace core;
class sendfile{
	public static function staticFile($path){
		if(!file_exists(STATICDIR.$_SERVER["REQUEST_URI"])){
			$err = core_errorHandler::getInstance();
			$err->setErrorPage(404);
		}
		else{
			//getting extension (lowercase)
			$ext = explode(".", $path);
			$ext = end($ext);
			$ext = strtolower($ext);
			
			//setting mime type
			switch($ext){
				case "css":
					header('Content-type: text/css');
				break;
				case "jpg":
				case "jpeg":
					header('Content-type: image/jpg');
				break;
				case "png":
					header('Content-type: image/png');
				break;
				case "gif":
					header('Content-type: image/gif');
				break;
				case "htm":
				case "html":
					header('Content-type: text/html');
				break;
				case "js":
					header('Content-type: application/x-javascript');
				break;
				case "php":
					die();
				break;
				default:
					header('Content-Disposition: attachment; filename="'.$request[$count-2].'.'.$request[$count-1].'"');
				break;
			}

			//setting std cache to one day. May be reised, it is static after all ;)
			header('Cache-Control: max-age=290304000, public');
			header_remove('pragma');
			header_remove('X-Powered-By');
			header_remove('Server');
			header_remove('Expires');
			
			//output file
			self::sendFile(STATICDIR.$path);
		}
	}
	
	public static function sendFile($absolutePath){
		readfile($absolutePath);
	}
	
}
?>
