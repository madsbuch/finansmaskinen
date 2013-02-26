<?
namespace app;

use \helper\layout\Element as Element;
use \helper\local as l;

class invoice extends \core\app
{
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
	function __construct($request)
	{
		$this->header = new \helper\header();
		parent::__construct($request);
	}

	/**
	 * display list of transactions and their status
	 */
	function index()
	{
		$html = $this->getTpl();


		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * see a single transaction and details
	 *
	 * @param null $id
	 */
	function view($id = null){

	}

	/**** Private aux ****/

	/**
	 * @return \helper\template
	 */
	private function getTpl()
	{
		$html = $this->getSiteAPI()->getTemplate();
		$html->setSecondaryTitle(__('Invoicing'));
		$html->addSecondaryNav(__('Create invoice'), '/invoice/add');
		$html->addSecondaryNav(__('View sent'), '/invoice');
		return $html;
	}

	/** REQUIRED FUNCTIONS **/

	function getOutputHeader()
	{
		return $this->output_header;
	}

	function getOutputContent()
	{
		return $this->output_content;
	}
}
