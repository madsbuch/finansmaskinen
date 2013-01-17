<?php
/**
* this is the API to the system. This is for application specific code
* like code for
*/

namespace start\finance;

class api extends \core\startapi{
	/**** callbacks for profile pages ****/
	/**
	* methods that should not be externally availanle
	*/
	public $external = array();
	
	/**** Interacting with the user system ****/
	
	/**
	* creaete a user for finansmaskien
	*
	* errorcodes:
	*	200 sucess
	*	0	user exist
	*/
	public static function createUser(

		\model\finance\platform\UserCreate $data,
		$companyType='company'){
		if(!is_null(self::findUser($data->mail)))
			return 0;
			
		//mongoDB handle
		$core = new \helper\core(null);
		$db = $core->getDB('mongo');
		
		//Create finance user object
		$fUser = new \model\finance\platform\User();
		
		//populate the user model
		$fUser->mail		= $data->mail;
		$fUser->name		= $data->name;
		$fUser->password	= self::hashPassword($data->pass, $fUser->mail);
		$fUser->activationKey = md5(time());
		
		//create user in the core system
		$auth = \core\auth::getInstance();
		$user = $auth->createUser();
		
		$fUser->coreSecret = $user->secret;
		$fUser->coreID = $user->uid;
		
		$groups = \core\groups::getInstance();
		//do main company group
		$mainGrp = $groups->createGroup();
		$groups->setMeta($mainGrp, 'name', 'Hele Virksomheden');
		
		//set permissions for new user, to main group
		$groups->addUser2Group($user->uid, $mainGrp, \permissions::ALL);
		

		//aps to subscribe
		$apps = \config\finance::$initApps[$companyType];
		foreach($apps as $appID => $name){
			$grp = $groups->createGroup($mainGrp);
			//name to show
			$groups->setMeta($grp, 'name', __($name));
			//so the app knows this is the main grp
			$groups->setMeta($grp, 'mainFor', $appID);
			$groups->addApp2Group($appID, $grp);
		}
		
		//insert the user in mongo
		$fUser = $fUser->toArray();
		$db->getCollection('financeUsers')->insert($fUser, array('safe' => true));
		$fUser = new \model\finance\platform\User($fUser);
		
		//inserting contact in finansmaskinen
		$rpc = new \helper\rpc\Finance('contacts');
		$contact = new \model\finance\Contact(array(
            'contactID'     => $mainGrp, //let main grp be the contact id, so we can recognize upon payment
			'apiCronUpdate' => true, //we always wanna have the users contact details
        ));

		$contact->apiID = $mainGrp;//treeID
		$contact->apiUrl = \config\config::$configs['finance']['settings']['protocol'] . 
			'://' . \config\config::$configs['finance']['domains']['rpc'];
		$rpc->add(\helper\model\Arr::export($contact));
		
		//sending the mail
		$mail = new \helper\mail();
		$mail->AddReplyTo('info@finansmaskinen.dk', 'Finansmaskinen');
		$mail->AddAddress($fUser->mail);
		$mail->SetFrom('info@finansmaskinen.dk', 'Finansmaskinen');
		
		$content = new \start\finance\layout\MailWelcome($fUser);
		$tpl = new \helper\template\DefMail();
		$tpl->appendContent($content);
		
		$mail->MsgHTML($tpl->generate());
		$mail->AltBody = $tpl->generateAlt();
		$mail->Subject = $tpl->generateSubject();
		$mail->Send();
		
		return $fUser;
	}
	
	public static function activate($key, $mongoID){
		$core = new \helper\core(null);
		$db = $core->getDB('mongo');
		$user = $db->getCollection('financeUsers')->findOne(array('_id' => new \MongoID($mongoID)));
		if(is_null($user))
			return false;
		$user['activated'] = true;
		$user = $db->getCollection('financeUsers')->save($user);
		return true;
	}
	
	/**
	* checks whether a given user exists
	*/
	public static function findUser($mail){
		$core = new \helper\core(null);
		$db = $core->getDB('mongo');
		$user = $db->getCollection('financeUsers')->findOne(array('mail' => $mail));
		
		return is_null($user['_id']) ? null : new \model\finance\platform\User($user);
	}
	
	/**
	* log a user in
	*
	* return	200 on sucess
	*			false   if the credentials are wrong
	*			0		if the user is not activated
	* the values doesn't make a lot of sense, but the idea, is that 200 validates
	* to true, and the others validates bot to false, on loosy comparison
	*/
	public static function login($mail, $pass){
		$user = self::findUser($mail);
		
		if($user && !$user->activated)
			return 0;
		
		if($user && $user->password === self::hashPassword($pass, $user->mail)){
			if(\core\auth::getInstance()->login($user->coreID, $user->coreSecret)){
				//save user in session
				$session = \core\session::getInstance();
				$session->user_holder = $user;

				//create log entry
				$log = new \model\log\core\Access();
				$log->userid = $user->coreID;
				$log->mail = $mail;
				//@TODO well maybe this is wrong ;)
				$log->interface = 'web';

				\core\logHandler::log($log);

				return true;
			}
		}
		return false;
	}
	
	/**
	* returns currently logges in user, if any
	*/
	public static function getUser(){
		$session = \core\session::getInstance();
		if($user = $session->user_holder){
			//unset($user->coreSecret, $user->coreID, $user->password);
			return $user;
		}
	}
	
	/**
	* aythenticates for API use
	*/
	public static function authenticate(){
		//make sure auth uses cache
		$cache = \helper\cache::getInstance('File', 'coreAuthentication');
		\core\auth::getInstance()->setCache($cache);
		
		//retrive user credencials
		$apiKey = \core\inputParser::getInstance()->getParameter('key');
		
		//retrive uid and secret from those credencials
		$user = self::getAPIKeys($apiKey, false);
		
		if(!$user)
			return;
		
		if($user->count() > 1)
			throw new \Exception('WOW! dafuq just happened (api key)');
		
		$user = new \model\finance\platform\APIUser($user->getNext());
		
		//login
		if($user)
			\core\auth::getInstance()->login($user->coreID, $user->coreSecret);
	}
	
	/**
	* create api key
	*
	* the algorithm should make sure that they are individually by user and
	* globally unique.
	*
	* it should be secure to use only the api key to authenticate a user
	*/
	public static function createAPIKeys(){			
		$user = self::getUser();
		
		$u = new \model\finance\platform\APIUser();
		
		//create kay and make sure its ready for parsing by url
		$u->apiKey = bin2hex(openssl_random_pseudo_bytes(32))
			. '-' . uniqid() . '-' . md5($u->mail);
		
		
		
		$u->mail = $user->mail;
		$u->coreSecret = $user->coreSecret;
		$u->coreID = $user->coreID;
		
		$core = new \helper\core(null);
		$db = $core->getDB('mongo');
		$db->getCollection('financeUsersAPI')->insert($u->toArray(), array('safe' => true));
		
		return $u;
	}
	
	/**
	* returns mongocursor
	*
	* authenticate either by mail or key
	*/
	public static function getAPIKeys($id, $mail=true){
		if(!$mail)
			$c = array('apiKey' => $id);
		else
			$c = array('mail' => $id);
			
		$core = new \helper\core(null);
		$db = $core->getDB('mongo');
		$user = $db->getCollection('financeUsersAPI')->find($c);
		
		return $user->count() > 0 ? $user : null;
	}
	
	/**
	* logs out a user
	*/
	public static function logout(){
		$session = \core\session::getInstance();
		$session->user_holder = null;
		$auth = \core\auth::getInstance();
		$auth->logout();
	}
	
	/**
	* returns an iterator over all apps
	*/
	public static function appIterator(){
		$auth = \core\auth::getInstance();
		if(!$auth->isLoggedIn())
			return null;
		return $auth->getApps();;
	}
	
	/**** Callbacks for apps ****/
	
	/**
	* get default group for insertion of external documents
	*
	* so, if other apps recieves a document, they need to now where to put it
	* this is done by this function.
	*
	* @param $app the app for the group the be returned
	* @param $treeID identifier of the company. we use treeid 
	*/
	static function getDefaultGrp($app, $treeID){
		
	}
	
	/**
	* returns a helper\template prepopulated with a template
	*
	* in this project I aim to use DOMDocument as template engine, this is
	* because it has a lot of advantages: no XSS, always valid XML.
	* on the eother hands there is some disadvangetes, which includes portation
	* from plain HTML to a DOM tree, but this can be done easely witht the proper
	* methods. 
	*/
	public static function getTemplate($for = 'html'){
		
		//user logged in?
		if(\core\auth::getInstance()->isLoggedIn()){
			$ret = new \helper\template\DefUser();
			
			$ret->setPrimaryTitle(__('Tools'));
			
			//create menu
			foreach(\core\auth::getInstance()->getApps() as $app){
				$objname = "\api\\".$app->name;
				$appname = $objname::getTitle();
				if($app->id == 3)
					$ret->setCompany($appname, '/'.$app->name);
				else
					$ret->addPrimaryNav($appname, '/'.$app->name);
			}
			
			//add companies that the user have access to
			foreach(\core\auth::getInstance()->getTrees() as $tree){
				$public = \api\companyProfile::getPublic($tree);
				$name = isset($public->Party->PartyName->Name->_content) ? 
					$public->Party->PartyName->Name->_content : __('Unnamed company');
				$ret->addCompanyList($name, '/index/changeTree/'.$tree);
			}
			
			return $ret;
		}
		return new \helper\template\Def();
	}
	
	/**** interacting with some sitespicefic variables ****/
	
	public static function getVar($name){
	
	}
	
	public static function setVar($name, $value){
	
	}
	
	/**
	* perform an atomic operation on some data
	*
	* this might f.eks. be used, if the system should administrate some inline
	* payment system.
	*
	* @param $name the name to perform the transaction on
	* @param $method a method to perform on the data
	* @return true om success, otherwise false
	*/
	public static function transaction($name, $method){
	
	}
	
	private static function hashPassword($pass, $mail){
		return  \core\util::hashCleartext($pass, $mail."298rhy2r3#");
	}
	
	/**** Interacting with the base system ****/
	
	/**
	* do the setup pages
	*/
	static function beforeExecution($request){
		//make it possible for the user to logout
		//@TODO, make it possible for at user to change treeID
		if($request->page == 'logout' && $request->app == 'main')
			return $request;
		
		//check if logged in
		if(!($iterator = self::appIterator()))
			return $request;
		
		//check whther the current app is in setup process
		if($request->app != 'main' 
			&& !$iterator[$request->app]->isSetup 
			&& $iterator[$request->app]->requireSetup){
			$request->page = 'setup';
			return $request;
		}
		
		//rewire to the first app, that needs to be setup
		foreach($iterator as $app){
			if(!$app->isSetup && $app->requireSetup){
				$request->app = $app->name;
				$request->page = 'setup';
				return $request;
			}
		}
        return $request;
	}
	
	/**
	* note that the setup is finished, and the user should not be prompet again.
	*/
	static function finishSetup($app){
		return \core\auth::getInstance()->doSetup($app);
	}
}

?>
