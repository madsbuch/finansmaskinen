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

		$bill = \api\billing::getOne($id);

		if($bill->draft)
			$widgets = $this->callAll('getBillingPreCreate', array($bill));
		else
			$widgets = $this->callAll('getBillingPostCreate', array($bill));

		$party = \api\contacts::getContact($bill->contactID);

        if(!isset($party->Party))
            $party = null;
        else
            $party = $party->Party;

		$view = new billing\layout\finance\View($bill, $party, $widgets);
		$html->appendContent($view);

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	function add()
	{

		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Regninger',
			'Tilføj ny regning'));

		$form = new billing\layout\finance\Form(array(),
			new \app\products\layout\finance\FormModal(),
			new \app\contacts\layout\finance\ModalForm());
        $form->addConfirmationMessage(\api\companyProfile::getMessageForAction('Bill'));
		$html->appendContent($form);

		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}

	function edit($id = null){
		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Regninger',
			'Rediger regning'));

		$bill = \api\billing::getOne($id);

		$form = new billing\layout\finance\Form($bill,
			new \app\products\layout\finance\FormModal(),
			new \app\contacts\layout\finance\ModalForm());
        $form->addConfirmationMessage(\api\companyProfile::getMessageForAction('Bill'));
        $html->appendContent($form);

		$this->output_header = $this->header->generate();
		$this->output_content = $html->generate();
	}

	/***************************** AJAX FUNCTION ******************************/

	function create()
	{
		$input = new \helper\parser\Post('model\finance\Bill');

		$input->alterArray(function ($arr) {

			//check if create bill was pressed
			if(isset($arr['finished']))
				$arr['draft'] = false;
			else
				$arr['draft'] = true;
			unset($arr['finished']);

			//check if create bill was pressed
			if(isset($arr['vatIncluded']))
				$arr['vatIncluded'] = true;
			else
				$arr['vatIncluded'] = false;

			//parsing the time
			if (isset($arr['paymentDate']))
				$arr['paymentDate'] =
					\DateTime::createFromFormat('d/m/Y', $arr['paymentDate'])->getTimestamp();
			else
				$arr['paymentDate'] = time();

			//some quantaty and unitprice stuff
			foreach ($arr['lines'] as &$il) {
				$il['amount'] = l::readValuta($il['amount']);

				//unset if fields are not set
				if(empty($il['account']))
					unset($il['account']);
				if(empty($il['productID']))
					unset($il['productID']);
				if(empty($il['vatCode']))
					unset($il['vatCode']);
			}

			//for some js plugins to work, the namefield must be set:
			unset($arr['trash']);

			return $arr;
		});

		$obj = $input->getObj();

		//var_dump($obj->toArray());
		//die();
		if (isset($obj->_id))
			\api\billing::update($obj);
		else
			$obj = \api\billing::create($obj);


		$this->header->redirect('/billing/view/' . (string)$obj->_id);

		$this->output_header = $this->header->getHeader();
		$this->output_content = '';
	}

	/**
	 * posts a bill
	 *
	 * @param null $id string
	 */
	function pay($id = null){
		$input = new \helper\parser\Post('model\Base');
		$input->alterArray(function ($arr) {
			$arr['assAcc'] = (int)$arr['assAcc'];
			$arr['liaAcc'] = (int)$arr['liaAcc'];
			return $arr;
		});

		$input = $input->getObj();
		\api\billing::bookkeep($id, $input->assAcc, $input->liaAcc);
		$this->header->redirect('/billing/view/' . (string)$id);
		$this->output_header = $this->header->getHeader();
		$this->output_content = '';
	}

	function remove($id){
		\api\billing::remove($id);

		$html = $this->getTpl();

		$m = new \helper\layout\MessagePage('Deleted!',
			'<p>'.__('Your bill was deleted.').'</p>');

		$html->add2content($m);

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
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
	 * @param null $file
	 * @return void
	 * @internal param \app\mongo $id id of the object
	 * @internal param $pic ,
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
