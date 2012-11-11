<?php
/**
* Helper class for making forms
*
* depends: helper_html, \core\inputParser
*/

namespace helper\layout;

/**
* creating forms and returning validated content
*
* descriptor layout:
* array(
*	array(attr => array()[, label => "label"], type => "input" ]),//null for no label
*	array(attr => array()[, label => "label1"],]), type => "input"//null for no label
* )
*
*
* for types following are currently supported for validation (all html5 input
* type will be supprted). type must be set in attr, otherwise validation returns false!
* * mail
* * text
* * password
* * textarea
*/
class Form extends LayoutBlock{

	/**
	* $db_prepare
	*
	* if true, all strings are escaped, so thay are ready for db
	*/
	public $dbPrepare = true;
	
	/**
	* contains validated data
	*
	* this variable contains validated data. Only data validated according to
	* descriptor is stored here. (if one wanna use array directly in db, it is not
	* acceptable that more fields than requested is submitted)
	*/
	public $data;
	
	/**
	*
	*/
	private $action;
	
	/**
	* update data, if something is posted?
	*/
	public $updateValue = true;
	
	/**
	* if validate is called, there is no readon to revalidate on getInput
	*/
	private $isValidated = false;
	
	/**
	* descriptor, contains description of form
	*/
	private $descriptor; 
	
	private $modal;
	
	function __construct($descriptor){
		$this->descriptor = $descriptor;
	}
	
	/**
	* Generates a form for output
	*
	*
	*/
	function generateForm(){
		$n = null;
		$html = new \helper\html($n, false);
		
		$attr = array("method" => "post");
		
		if($this->action)
			$attr['action'] = $this->action;
		if(!empty($this->modal))
			$attr['id'] = 'ajaxform';
		
		$form = new \helper\html\Form($attr);
		
		foreach($this->descriptor as $input){
			//add form element
			if(isset($input['label']))
				$form->addLabel($input['attr']['name'], $input['label']);
			$form->addInput($input['attr']);
		}
		
		if(!empty($this->modal)){
			$form = $form->getBlock();
			$ret = array('tag' => 'div', 'attr' => 
				array('id' => 'modal', 'title' => $this->modal),
				$form);
			return $ret;
		}
		
		return $form->getBlock();
	}
	
	/**
	*alias of generateForm
	*/
	function generate(){
		return generateForm();
	}
	
	/**
	* validates given input
	*
	* if false, error.
	*/
	function validateInput(){
		$input = \core\inputParser::getInstance();
		$util = new \core\util();
		$field = $input->getPost();
		
		//check all fields
		foreach($this->descriptor as &$input){
			//nothing to check
			if(!isset($input['attr']['name']))
				continue;
			
			//check if formfield is set
			if(!isset($field[$input['attr']['name']]))
				return false;
			
			//check that incomming string is not more than eventual maxlenght
			if(isset($input['attr']['maxlength']))
				if(strlen($field[$input['attr']['name']]) > $input['attr']['maxlength'])
					return false;
			
			//validate
			$input['attr']['type'] = strtolower($input['attr']['type']);
			switch($input['attr']['type']){
				case "mail":
					if(!$util->validateMail($field[$input['attr']['name']]))
						return false;
				break;
				case "text":
				case "password":
				case 'hidden':
					//what to validate? only db check is needed
				break;
				default:
					return false;
				break;	
			}
			
			/**
			* the string is validated!! :D
			*
			* we do not dbPrepare the keys, as theyh are checked against the descriptor
			* the only sql injection, is if the programmer designs one ;)
			*/
			if($this->updateValue)
				$input['attr']['value'] = $field[$input['attr']['name']];
				
			$this->data[$input['attr']['name']] = $field[$input['attr']['name']];
		}
		return true;
	}
	
	/**
	* returns validated indput
	*/
	function getInput(){
		if(!$this->isValidated)
			if(!$this->validateInput())
				return false;
		return $this->data;
		
	}
	
	/**
	* set action path
	*/
	function setAction($action){
		$this->action = $action;
	}
	
	/**
	* prepare the form as a modalform
	*
	* invoking tthis, makes it not show up.
	*
	* @param	$title	title of the box.
	*/
	function setModal($title){
		$this->modal = $title;
	}
}

?>
