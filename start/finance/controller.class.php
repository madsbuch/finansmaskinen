<?php
namespace start\finance;
class main extends \core\app implements \core\framework\Output
{

	public static $requireLogin = false;
	
	private $auth;
	
	function __construct($r){
		parent::__construct($r);
		$this->header = new \helper\header();
		$this->auth = \core\auth::getInstance();
	}
	
	/**
	* Index function
	*
	* this creates the overall frontpage
	*/
	public function index(){
		//check if somebody is logged in
		if($this->auth->isLoggedIn()){
			//the user was logged in, we redirect to the main page
			$this->header->redirect("/index/apps");
			$this->output_header = $this->header->generate();
			$this->output_content = '';
			return;//stop execution
		}
		
		//check if somebody tries to log in
		$post = new \helper\parser\Post('\model\Base');
		$data = $post->getObj();
		
		$err = null;

		if($data && ($err = api::login($data->mail, $data->password))){
			//if login, then redirect to home
			$this->header->redirect("/index/apps");
			$this->output_header = $this->header->generate();
			$this->output_content = '';
			return;
		}
		
		if($err === 0){
			$msg = new \helper\layout\UserMsg('Din bruger er ikke aktiveret.
			Skal vi sende din aktiveringsmail igen?');
			$msg->setButton('Send aktiveringskode', '/index/activate/resend');
			$msg->setTitle('Ikke aktiveret?');
			$this->setUserMsg('index_user_not_activated', $msg);
		}
				
		if($err === false){
			$msg = new \helper\layout\UserMsg('Kan du huske din adgangskode? Vil du have mulighedden
				for at nulstille den?');
			$msg->setButton('Nulstil adgangskode', '/index/password/reset');
			$msg->setTitle('Ups, nogle informationer passede ikke.');
			$this->setUserMsg('index_user_wrong_cred', $msg);
		}
		
		/**** echo out some frontpage ****/
		$html = $this->getTpl('index');
		$html->setMsg($this->getUserMsg());
		$html->add2content(new layout\Home());
		//$this->output_header = $this->page->getHeader();
		$this->output_content = $html->generate();
		$this->clearUserMsg();
	}
	
	/**
	* logout, redirects to index
	*/
	public function logout(){
		api::logout();
		$this->header->redirect("/index");
		$this->output_header = $this->header->generate();
		$this->output_content = "";
	}
	
	/**
	* adm
	*
	* users administration page
	*/
	public function adm(){
	}
	/*Return ul for children*/
	private function recGrp($grpID){
		$grps = \core\groups::getInstance();
		$groups = $grps->getGroups();
		
		$str = "";
		
		if(isset($groups['children'])){
			$ret['tag'] = "ul";
			foreach($groups['children'] as $c)
				$ret['tag'][] = array('tag' => 'li', 'content' => '');
		}
				
		
	}
	
	/**
	* Apps
	*
	* main page for logged in users.
	* shows available apps
	*/
	public function apps(){
		$auth = \core\auth::getInstance();
		$html = $this->getTpl();
		
		//test if a user is logged in
		if(!$auth->isLoggedIn()){
			//set message to user
			$hc = new \helper\core(false);
			$hc->addUsrMsg(__('You are not logged in, log in.'));
			//and redirect
			$this->header->redirect("/index");
			$this->output_header = $this->header->generate();
            $this->output_content = '';
			return;//stop execution
		}
		
		$widgets = $this->callAll('getWidget');
		
		$html->appendContent(\helper\layout\Element::heading('Dashboard',
			'Her har du et overblik over din virksomhed'));
		
		$html->add2content(new \start\finance\layout\Main($widgets));
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	* page for creating user
	*/
	public function createUser(){
		$post = new \helper\parser\Post('\model\finance\platform\UserCreate');
		
		$object = $post->getObj();
		$db = new \core\db('finance');
		
		if($object){
			//validate all fields
			$err = false;
			//@TODO BETA
			if($object->beta !== 'jegtester'){
				$msg = new \helper\layout\UserMsg('Hov, det var ikke den rigtige betakode ;-)');
				$msg->setTitle('Forkert betakode');
				$this->setUserMsg('index_user_wrong_beta', $msg);
				$err = true;
			}
			
			//password is the same
			if($object->pass !== $object->repass){
				$msg = new \helper\layout\UserMsg('De to koder du har skrevet, er ikke ens.');
				$msg->setTitle('Koderne matchede ikke.');
				$this->setUserMsg('index_nonmatching_pass', $msg);
				$err = true;
			}
			
			//mail valid
			if(!filter_var($object->mail, FILTER_VALIDATE_EMAIL)){
				$msg = new \helper\layout\UserMsg('Mailadressen skal være gyldig,
				ellers kan vi ikke sende beskeder til dig.');
				$msg->setTitle('Mailadressen var ikke gyldig.');
				$this->setUserMsg('index_nonvalidated_mail', $msg);
				$err = true;
			}
			
			if(!$err && !($object = api::createUser($object))){
				$msg = new \helper\layout\UserMsg('Nogen har allerede brugt denne mail til en bruger?');
				$msg->setTitle('Prøv igen med et andet brugernavn.');
				$this->setUserMsg('index_wrong username', $msg);
			}
		}
		
		if($err || !$object){
			$this->header->redirect("/index/");
			$this->output_header = $this->header->generate();
			$this->output_content = '';
			return;
		}
		
		$html = $this->getTpl();
		$html->appendContent(new layout\SignupFinish($object));
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	function personal(){
		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading(__('Personal settings'),
			'Administrer API nøgler og personlige egenskaber'));
		$html->appendContent(new layout\Personal(
			($u = \start\finance\api::getUser()),
			\start\finance\api::getAPIKeys($u->mail)));
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	/**
	* page for activating account
	*/
	public function activate($activationKey=null, $mongoID=null){
		$html = $this->getTpl();
		if($activationKey == 'resend'){
			$c = new \helper\layout\MessagePage('Gensendt kode',
				'<p>Din kode er nu gensendt, log ind på din mail og tryk på linket i mailen</p>');
		}
		
		elseif(api::activate($activationKey, $mongoID)){
			$c = new \helper\layout\MessagePage('Aktiveret!',
				'<p>Din konto er nu aktiveret, og du kan logge ind</p>');
		}
		else{
			$c = new \helper\layout\MessagePage('Hmm?',
				'<p>Enten findes brugeren ikke, ellers også skete der en fejl.</p>
				<p>prøv igen senere</p>');
		}
		
		$html->appendContent($c);
		
		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}
	
	public function password($action=null){
		$html = $this->getTpl();
		$c = new \helper\layout\MessagePage('Nulstilling',
				'<p>Du skulle gerne have modtaget en mail, med et link til at
				nulstille dit password (NYI)</p>');
		$html->appendContent($c);
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	/**** ALL THE PUBLIC INFORMATION PAGES ****/
	
	public function price(){
		$html = $this->getTpl('price');
		$html->add2content(new layout\Price());
		//$this->output_header = $this->page->getHeader();
		$this->output_content = $html->generate();
	}
	
	
	public function about(){
		/**** echo out some content ****/
		//static page, let's try some caching :D
		/*$cache = \helper\cache::getInstance('File', 'financeMainHTML');
		$o = $cache->get('aboutPage');
		
		if(!is_null($o) && false)
			$this->output_content = $o;
		else{
			$html = $this->getTpl('about');
			$html->add2content(new layout\About);
			$this->output_content = $html->generate();
			//cachinf for full 2 hours ;)
			$cache->set('aboutPage', $this->output_content, 7200);
		}*/
		$html = $this->getTpl('about');
		$html->add2content(new layout\About());
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	
	/**
	* shows the auth struct 
	*/
	function struct(){
		/**** echo out some content ****/
		$html = $this->getTpl('about');
		$html->add2content(\helper\layout\Element::heading('Struktur', 'Din adgang til denne virksomhed'));
		
		$groups = \helper\core::getAllGroups();
		
		$html->add2content(new layout\ShowStruct($groups));
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}
	
	/*************************** AJAX METHODS *********************************/
	
	/**
	* clears the user message queue
	*/
	function clearqueue($key=null){
		
	}
	
	/**
	* ajax uploading of files
	*/
	function upload($app=null){
		$fh = new \helper\file($app);
		
		//@TODO make sure user is logged in
		
		var_dump($fh->fromPost($_FILES));
		
		$this->output_header = null;
		$this->output_content = '<html><head></head><body>
		
		<form method="post" enctype="multipart/form-data">
			<input name="userfile" type="file" />
			<input type="submit" value="Send files" />
		</form>
		
		</body></html>';
	}
	
	/**
	* change the treestructure for the user, to another company
	*/
	function changeTree($tree = null){
		if(is_null($tree)){
			$this->header->redirect('/index/apps');
			$this->output_header = $this->header->generate();
			$this->output_content = '';
		}
		$tree = (int) $tree;
		
		\core\auth::getInstance()->reFetch($tree);
		
		$this->header->redirect('/index/apps');
		$this->output_header = $this->header->generate();
		$this->output_content = $tree;
	}
	
	
	/**** global accessible lists ****/
	
	/**
	* get list of currency codes that match term
	*/
	function currencies($term=''){
		$term = mb_strtoupper($term);
		$db = \core\db::getInstance('finance');
		$sth = $db->dbh->prepare("SELECT * FROM countries WHERE `iso4217` LIKE ?
			LIMIT 0 , 10");
		
		$sth->execute(array('%'.$term.'%'));
		
		$ret = array();
		$retA = array();
		
		foreach($sth->fetchAll() as $c){
			$ret['label'] = $c['iso4217'];
			$ret['desc'] = $c['currency_name'];
			$ret['id'] = 0;
			$ret['category'] = mb_strtoupper(substr($c['iso4217'], 0, 1));
			$retA[] = $ret;
		}
		
		$this->header->setMime('json');
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = json_encode($retA);
	}
	
	/**
	* get a currency code object
	*
	* if from and to is provided, only the exchange rate is returned, if value
	* is provided, it is assumed to be the from rate
	*/
	function currency($from = null, $to = null, $amount = 1){
		$er = new \helper\fetcher\ExchangeRate();
		
		$date = isset($this->param['date']) ? $this->param['date'] : time();
		$amount = \helper\local::readValuta($amount, $from);
		
		if($rate = (float) $er->getRate($from, $to, $date)){
			$ret = array();
			$ret['fromC'] = $from;
			$ret['toC'] = $to;
			$ret['fromA'] = \helper\local::writeValuta($amount, $from);
			$ret['toA'] = \helper\local::writeValuta($rate * $amount, $to);
			$ret['rate'] = $rate;
			$ret['timestamp'] = $date;
			$ret['date'] = date("Y-m-d", $date);
		}
		else
			$ret = null;
		
		$this->header->setMime('json');
		$this->output_header = $this->header->getHeader();
		$this->output_content = json_encode($ret);
	}
	
	/**
	* returns the file, with the right mime type
	*
	* if one wants to add filename, it should be done via url:
	* finansmaskinen.dk/index/file/someID/someAPP/fileName.something
	*
	* otherwise the file will get the name of the filename
	*
	* $force, pending??
	*/
	function file($file=null, $app=null, $force=false){
		$fh = new \helper\file($app);
		$file = $fh->getFile($file);
		
		if(is_null($file)){
			$this->errorPage(404);
			return;
		}
		
		//setting the mime
		$this->header->setMime($file->mime);
		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $file->file->getFile();
	}
	
	/**
	* forslag til boksen i højre hjørne
	*
	* //@TODO add caching if possible
	*/
	function actionSuggest(){
		$db = \core\db::getInstance('finance');
		$ip = \core\inputparser::getInstance();
		
		//@TODO make sure input is sanitized
		$term = $ip->getParameter('term');
		
		$stmt = $db->query('SELECT url, action, description, MATCH (`action`,description,keywords) AGAINST
			(\''.$term.'\') AS score
			FROM actions WHERE MATCH (`action`,description,keywords) AGAINST
			(\''.$term.'\') AND searchable = 1');
		
		$stmt->execute();
		$t = false;
		$this->output_content = '[ ';
		//format set to auth-array data
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			if($t)
				$this->output_content .= ",";
			$this->output_content .= '{"url" : "'.$row['url'].'", "label" : "'.$row['action'].'", "description" : "'.$row['description'].'"}'."\n";
			$t = true;
		}
		
		if(!$t){
			$this->output_content .= '{"url" : "#", "label" : "Ingen resultater", "description" : "Ingen resultater, prøv med nogle andre ord"}'."\n";
		}
		
		$this->output_content .= ' ]';
		
		$this->header->setMime('js');
		
		$this->output_header = $this->header->generate();
	
		/*
	
		nedenstående query laver den naturlige søgning :D
		@TODO husk at der her skal valideres hårdt! (a-å A-Å 0-9 KUN)
	
		SELECT url, action, description, MATCH (`action`,description,keywords) AGAINST
		('se ubetalte fakturaer') AS score
		FROM actions WHERE MATCH (`action`,description,keywords) AGAINST
		('se ubetalte fakturaer')
		*/
	}

	
	function appExpired($app){
		$html = new \helper\html($this->page);
		$html->setTitle("FEJL!!! ".$app);
		
		$this->output_header = $this->page->getHeader();
		$this->output_content = $this->page->getContent();
	}
	
	/**** Required functions ****/
	
	function errorPage($errornum){
		/**** echo out some content ****/
		$html = $this->getTpl();
		if($errornum == 403){
			$c = new \helper\layout\MessagePage(__('Woops'),
			'<p>'.__('Well, it doesn\'t seem that you are allowed to see this page... Try to login maybe?').'</p>');
		}
		elseif($errornum == 404){
			$this->header->setResponse(404);
			$c = new \helper\layout\MessagePage('ARG! 404',
			'<p>Denn side findes vist ikke.</p>');
		}
		
		
		$html->appendContent($c);
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * takes an exception, shows it to the user :D
	 *
	 * exception messages are translated.
	 *
	 * @param \core\framework\the $e
	 */
	function handleException($e){
		/**** echo out some content ****/
		$html = $this->getTpl();
		if($e instanceof \exception\PermissionException){
            $this->header->setResponse(403);
			$c = new \helper\layout\MessagePage(__('Woops'),
				'<p>'.__('Well, it doesn\'t seem that you are allowed to see this page... Try to login maybe?').'</p>');
		}
		elseif($e instanceof \exception\PageNotFoundException){
			$this->header->setResponse(404);
			$c = new \helper\layout\MessagePage('ARG! 404',
				'<p>'.__('The requested page does not exist.').'</p>');
		}
		elseif($e instanceof \exception\UserException){
			$c = new \helper\layout\MessagePage('En fejl?',
				'<p>'.$e->getMessage().'</p>');
		}
		else{
			$this->header->setResponse(500);
			$c = new \helper\layout\MessagePage('Bah 500',
				'<p>'.__('Some fatal internal error happened.').'</p>');
		}

		$html->appendContent($c);
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	function getHeader(){
		return $this->getOutputHeader();
	}

	function getBody(){
		return $this->getOutputContent();
	}
	
	
	function addAPIKey(){
		\start\finance\api::createAPIKeys();
		$this->header->redirect('/index/personal');
		$this->output_header = $this->header->getHeader();
		$this->output_content = '';
	}
	
	function getOutputHeader(){
		return isset($this->output_header) ? $this->output_header : null;
	}
	
	function getOutputContent(){
        if(!isset($this->output_content))
            throw new \Exception('No output was generated');
		return $this->output_content;
	}
	
	/**** private functions ****/
	
	private function getTpl($page = 'none'){
		$html = $this->getSiteAPI()->getTemplate();

		if(!$this->auth->isLoggedIn()){
			$html->addNav(__('Home'), '/', $page=='index'?true:false);
			$html->addNav(__('Price'), '/index/price', $page=='price'?true:false);
			$html->addNav(__('About'), '/index/about', $page=='about'?true:false);
		}
		return $html;
	}
}
?>
