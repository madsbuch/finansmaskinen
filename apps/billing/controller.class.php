<?
/**
 * Company contacts
 *
 * yeah, this is company contacts, personal contacts have to have another name :S
 */
namespace app;

use \helper\local as l;

class billing extends \core\app
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
	function __construct()
	{
		$this->header = new \helper\header();
	}

	/**
	 * Create some index page for the billing apps
	 */
	function index()
	{
		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Regninger',
			'Alle dine regninger'));

		//create the addlink
		$html->appendContent(\helper\layout\Element::primaryButton(
			'/billing/add',
			'<i class="icon-plus" /> ' . __('Insert bill')));

		$iterator = \api\billing::getList();

		//@TODO awareness of profile
		$list = new billing\layout\finance\Listing($iterator);

		$html->appendContent($list->generate());

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * view a bill
	 */
	function view($id = null)
	{
		$html = $this->getTpl();

		$html->appendContent(\helper\layout\Element::heading(__('Billing'), __('Details for bill')));

		$widgets = $this->callAll('getBillingPostCreate', array(null));

		$view = new billing\layout\finance\View(\api\billing::getOne($id), $widgets);
		$html->appendContent($view);

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	function add()
	{

		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Regninger',
			'Tilføj ny regning'));

		$form = new billing\layout\finance\Form();
		$html->appendContent($form);

		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}

	/***************************** AJAX FUNCTION ******************************/

	function create()
	{
		$input = new \helper\parser\Post('model\finance\Bill');

		$input->alterArray(function ($arr) {
			if (isset($arr['isPayed']) && $arr['isPayed'] == 'on')
				$arr['isPayed'] = true;
			else
				$arr['isPayed'] = false;

			$arr['draft'] = true;

			//parsing the time
			if (isset($arr['Invoice']['IssueDate']))
				$arr['Invoice']['IssueDate'] =
					\DateTime::createFromFormat('d/m/Y', $arr['Invoice']['IssueDate'])->getTimestamp();
			else
				$arr['Invoice']['IssueDate'] = time();

			//some quantaty and unitprice stuff
			foreach ($arr['Invoice']['InvoiceLine'] as &$il) {
				$il['Price']['PriceAmount'] = $il['unitPrice'] * $il['InvoicedQuantity'];
				$il['Price']['PriceAmount']['_content'] = l::readValuta($il['Price']['PriceAmount']['_content']);
				unset($il['unitPrice']);
			}

			//for some js plugins to work, the namefield must be set:
			unset($arr['trash']);

			return $arr;
		});

		$obj = $input->getObj();

		//var_dump($obj->toArray());
		//die();

		$obj = \api\billing::create($obj);


		$this->header->redirect('/billing/view/' . (string)$obj->_id);

		$this->output_header = $this->header->getHeader();
		$this->output_content = '';
	}

	/**
	 * takes a file, and returns JSON for the blueimp plugin
	 */
	function fileUpload($return = 'json')
	{

	}

	/**
	 * deletes a file
	 *
	 * @param $id mongo id of the object
	 * @param $pic,
	 */
	function fileDelete($file = null)
	{

	}

	function fileGet($file = null)
	{

	}


	/**** Required functions ****/

	/**
	 * setup and introduce the app, when it's just activated
	 *
	 * this is completely sealed. that implies, that only this function is called,
	 * untill setup is reported finished.
	 */
	function setup($done = false)
	{
		if ($done) {
			$this->getSiteAPI()->finishSetup('billing');
			$this->header->redirect('/index');
		}

		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Regninger',
			'En lille introduktion'));
		$html->appendContent(new billing\layout\finance\Setup());

		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}

	function getOutputHeader()
	{
		return $this->output_header;
	}

	function getOutputContent()
	{
		return $this->output_content;
	}

	/**** private functions ****/
	private function getTpl()
	{
		$html = $this->getSiteAPI()->getTemplate();

		$html->setSecondaryTitle(__('Billing'));
		$html->addSecondaryNav(__('View bills'), '/billing');
		$html->addSecondaryNav(__('Add bill'), '/billing/add');

		return $html;
	}
}
