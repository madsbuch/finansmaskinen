<?php

class start_financeAdm_index{

	public static $requireLogin = false;

	function __construct(){
		$this->page = new helper_page();
		$this->header = new helper_header();
		
		$auth = core_auth::getInstance();
		$this->html = new helper_html($this->page);
		
		if($auth->isLoggedIn()){
			$this->html->addTopNavItem("Log ud", "logout", "index");
			$this->html->addTopNavItem("Administrer", "adm", "index");
		}
		else {
			$this->html->addTopNavItem("Hjem", "index", "index");
		}
	}
	
	/**
	* Index function
	*
	* currently only loginbox :S
	*/
	public function index(){
		$util = new core_util();
		
		//descriptor
		$formArr = array(
			array('attr' => array("type" => "text", "id" => "mail",
				"name" => "mail", "class" => "form", "style" => "width:300px;"),
				'label' => 'Mail'),
				array('attr' => array("type" => "password", "id" => "password",
				"name" => "password", "class" => "form", "style" => "width:300px;"),
				'label' => 'Kode'),
				array('attr' => array("type" => "submit", "value" => "Log ind",
				"class" => "form")),);
		
		$form = helper_layout::blockHelper("form", $formArr);
		
		//check if there is any input
		if($form->validateInput()){
			$input = $form->getInput();

			//input is correctly validated, try log user in
			if($input['password'] === "123456" && $input['mail'] === "654321"){
				$this->header->redirect("/index/admIndex");
				$this->output_header = $this->header->generate();
				return;//stop execution
			}
			
		}
		
		//add something to layout
		$html = $this->html;
		$html->add2content($form->generateForm());
		
		$html->setTitle("FinansMaskinen");
		
		$this->output_header = $this->page->getHeader();
		$this->output_content = $this->page->getContent();
	}
	
	/**
	* Index function
	*
	* currently only loginbox :S
	*/
	public function admIndex(){
		$html = $this->html;
		$html->setTitle("God");
		
		$db = core_db::getInstance(config_config::$configs['finance']);
		$users = $db->getList('users');
		
		var_dump($users);
		
		foreach($users as $u){
			$this->page->add2content($this->html->paragraph($u['mail']));
		}
		
		$this->output_header = $this->page->getHeader();
		$this->output_content = $this->page->getContent();
	}
	
	/**
	* logout, redirects to index
	*/
	public function logout(){
		$auth = core_auth::getInstance();
		$auth->logout();
		$this->header->redirect("/index");
		$this->output_header = $this->header->generate();
		$this->output_content = "";
	}
	
	/**
	* Required functions
	*/
	function getOutputHeader(){
		return $this->output_header;
	}
	
	function getOutputContent(){
		return $this->output_content;
	}
}

?>
