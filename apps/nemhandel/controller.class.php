<?
namespace app;

use \helper\local as l;

class nemhandel extends \core\app{
	/**
	* requireLogin
	*/
	static public $requireLogin = true;
	
	/**
	* requireLogin
	*/
	static public $grpSelector = false;
	
	/**
	* construction
	*/
	function __construct(){
		$this->header = new \helper\header();
	}

	/**
	 *
	 */
	function index(){
		$html = $this->getTpl();
		
		$html->appendContent(\helper\layout\Element::heading('Nemhandel', __('Administrate Nemhanden integration')));

		
		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * shows a single
	 * @param $id
	 */
	function view($id){

	}
	
	function getOutputHeader(){
		return $this->output_header;
	}
	
	function getOutputContent(){
		return $this->output_content;
	}
}
