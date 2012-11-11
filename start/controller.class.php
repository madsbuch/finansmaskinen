<?php
/**
* This is the default start controller. If there is no route, this one is used
*
*
*/
class start_index{

	public static $requireLogin = false;

	function __construct(){
		$page = new helper_page();
		$layout = new helper_layout($page);
		
		//adding title
		$layout->setTitle('Appframework');
		
		//add those topNavs items
		//$layout->addTopNavItem('title', 'controller');
		
		$content = array(
			'tag' => 'h1',
			'content' => 'Fejlside'
		);
		$layout->add2content($content);
		
		$content = array(
			'tag' => 'p',
			'content' => 'Du er kommet til denne side, fordi vi har nogle 
			problemer med systemet. Prøv snarest igen.'
		);
		$layout->add2content($content);
		
		//add those topNavs items
		$layout->addMenuItem('Om', 'about');
		$layout->addMenuItem('kontakt', 'contact');
		
		//add some text to footer
		$layout->setFooter('AppFramework, vælg en portal');
		
		$this->output_content = $page->getContent();
		$this->output_header = $page->getHeader();
	}
	
	public function index(){
		$auth = core_auth::getInstance();
		//$userid = $auth->createUser('config', 'madspbuch@gmail.com', 'false');
		//$this->output_content = "FrontPage!<br />Bruger oprettet: $userid";
	}

}

?>
