<?php
/**
 * Request Handler
 *
 * Handles all requests and routes to the right classes.
 *
 * @package appFramework
 * @since v1
 * @author Mads Buch <madspbuch@gmail.com>
 */

/**
 * core request handler
 *
 * @uses \core\inputParser, \core\errorHandler, \core\appHandler
 */
namespace core;
class reqHandler{
	
	/**
	* Initialising page
	*/
	static function init(){
		//getting objHandles
		$inputParser = \core\inputParser::getInstance();
		$eh = \core\errorHandler::getInstance();
		$auth = \core\auth::getInstance();
		
		//profile name
		$profile = $inputParser->getSite();
		$api = 'start\\'.$profile.'\api';

		/**
		 * request object, this keeps details on the request:
		 *
		 * in what format should return be, what was requested etc.
		 */
		$request = new \model\core\Request();
		
		//initialising the request
		$request->app = $inputParser->getApp();
		$request->page = $inputParser->getPage();
		$request->ui = 'app';
		$request->fileType = $inputParser->getFileType();
		$request->arguments = $inputParser->getArgs();
		
		/**** check subdomains to see where the request should be dispatched to ****/
		$domain = $inputParser->getReverseDomain();//$domain[2] contains level 1 sub


		//handle subdomains, might benecesary to put this in composition:
		$subDomain = isset($domain[2]) ? $domain[2] : null;
		//static files
		if($subDomain == "static"){
			\core\sendFile::staticFile($inputParser->getURI());
			return;
		}
		elseif($subDomain == 'rpc'){
			$request->ui = 'rpc';
			$request = \core\rpc::parseRequest($request);
			$api::authenticate();
		}
		elseif($subDomain == 'rest'){
			$request->ui = 'rest';
			$api::authenticate();
		}
		elseif($subDomain == 'soap'){
			$request->ui = 'soap';
			$api::authenticate();
		}
		elseif($subDomain == 'www'){
			//it's a webapp :D
		}
		else{
			//it's nothing... :/ redirect to frontpage
            //@TODO really bad hack... we don't wanna destroy the programflow

			header('location: http://www.'.$domain[1].'.'.$domain[0]);
			exit();
		}

        /** just preexecution stuff **/

		//setting some more variables
		$mainPageClass = 'start\\'.$profile.'\\' . (($request->ui == 'app') ? 'main' : $request->ui);
        //sets default errorhandler
        $eh->setOutput(new $mainPageClass($request));
        $request = $api::beforeExecution($request);

		/* EXECUTING MAIN SITE */

		/** generate the page **/

		//setting object name for the app
		if($request->app == "main"){
			$objName = $mainPageClass;
			//checking of the start page exists
			if(!class_exists($objName)){
				if(DEBUG)
					trigger_error('Specified site doesn\'t exist, using default.');
				//if not, set the default start page
				$objName = 'start\main';
			}
		}
		else
			$objName = '\\'.$request->ui.'\\'.$request->app;

		//create apphandler and set output handler for errors
		if(!class_exists($objName))
			throw new \exception\PageNotFoundException(__('App not found'));

		$appHandler = new $objName($request);

		if($appHandler instanceof \core\framework\Output)
			$eh->setOutput($appHandler);

		//checking wether page exists
		if(!is_callable(array($objName, $request->page))){
			throw new \exception\PageNotFoundException(__('Page %s does not exist in app %s.',$request->page,$objName));
			$o = 'start\\'.$profile.'\main';
			
			/** throwing error page **/

			//if callback is provided
			if(is_object($request->callback))
				throw new \exception\PageNotFoundException(__('%s doen\'t exist in %s',$request->page , $request->app));
			else{
				//creating app
				$appHandler = new $o($request);
				//calling page
				$appHandler->errorPage(404);
			}
		}
		//checking permission (the app class file is included here)
		elseif(!($objName::$requireLogin && !$auth->appAuthorized($request->app))){
			//setting appdir
			if($request->app != "index")
				define("__APPDIR__", APPDIR."/".$request->app);
			elseif(is_dir(ROOT."start/".$inputParser->getSite()))
				define("__APPDIR__", ROOT."start/".$inputParser->getSite());
			else
				define("__APPDIR__", ROOT."start/");
			
			if(DEBUG){
				$debug = \core\debug::getInstance();
				$debug->eventByTime("appStart");
				$debug->startTimer("app execution time");
			}
			
			//calling page
			call_user_func_array(array($appHandler, $request->page), $request->arguments);
			
			if(DEBUG){
				$debug = \core\debug::getInstance();
				$debug->eventByTime("appStop");
				$debug->add2statistics("app execution time");
			}
		}
		//if user id not authorized
		else{
			throw new \exception\PermissionException("You don't have permission to access selected page");
			//hmm, rather handle this the ordinary way?!
			$o = 'start\\'.$profile.'\main';

			if(is_object($request->callback))
				$request->callback->handleError(403);
			else{
				//creating app
				$appHandler = new $o($request);
				//calling page
				$appHandler->errorPage(403);
			}
		}
		
		\core\appHandler::doOutput($appHandler);
	}
}


?>
