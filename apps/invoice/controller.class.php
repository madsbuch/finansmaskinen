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
	 * the index
	 */
	function index()
	{
		$html = $this->getTpl();

		$html->appendContent(\helper\layout\Element::heading('Fakturering', 'Alle fakturaer'));

		//create the toplink
		$html->appendContent(Element::primaryButton(
			'/invoice/add',
			'<i class="icon-plus" /> ' . __('Create invoice')));

		$list = \api\invoice::get();
		$html->appendContent(new \app\invoice\layout\finance\Listing($list));

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

    /**
     * create new invoice
     */
    function add()
	{
		$html = $this->getTpl();

		$html->appendContent(\helper\layout\Element::heading('Fakturering', 'Opret faktura'));

		$view = new invoice\layout\finance\Form(null, new \app\products\layout\finance\FormModal());
        $view->addConfirmationMessage(\api\companyProfile::getMessageForAction('Invoice'));

		if ($this->param['reciever'])
			$view->defaultContact($this->param['reciever']);

		$html->appendContent($view);

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

    /**
     * edit an existing invoice, that is a draft
     *
     * @param null $id
     */
    function edit($id = null)
    {
        $html = $this->getTpl();
        $html->appendContent(\helper\layout\Element::heading(__('Invoicing'),
            __('Edit your invoice')));

        $invoice = \api\invoice::getOne($id);

        if ($invoice->draft){
            $view = new invoice\layout\finance\Form($invoice, new \app\products\layout\finance\FormModal());
            $view->addConfirmationMessage(\api\companyProfile::getMessageForAction('Invoice'));
            $html->appendContent($view);
        }
        else
            $html->add2content('<div class="alert alert-info">' . __('Invoice is not a draft') . '</div>');

        $this->output_header = $this->header->getHeader();
        $this->output_content = $html->generate();
    }

	/**
	 * shows page for mailing invoice
	 */
	function mail($id = null)
	{
		$html = $this->getTpl();

		$html->appendContent(\helper\layout\Element::heading('Fakturering', 'E-mail faktura'));

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * view an invoice
	 */
	function view($id = null)
	{
		$html = $this->getTpl();

		$html->appendContent(\helper\layout\Element::heading(__('Invoicing'), __('Details for invoice')));

		$invoice = \api\invoice::getOne($id);

		if ($invoice->draft)
			$widgets = $this->callAll('getInvoiceDraft', array($invoice));
		else
			$widgets = $this->callAll('getInvoicePostCreate', array($invoice));

		$view = new invoice\layout\finance\View($invoice, $widgets);
		$html->appendContent($view);

		$this->output_header = $this->header->getHeader();
		$this->output_content = $html->generate();
	}

	/**
	 * returns raw dump of the OIOUBL invoice linked to an xsl style
	 */
	function viewRaw($id = null)
	{
		$xml = \api\invoice::getInvoiceAsXML($id, false,
			'http://static.finansmaskinen.dev/resources/oioubl/xsl/InvoiceHTML.xsl');

		$this->header->setMime('xml');
		$this->output_header = $this->header->getHeader();
		$this->output_content = $xml;
	}

	/**
	 * reads template and output from http parameters, and outputs as requested type with requested template
	 *
	 * @param null $id id of invoice
	 */
	function export($id = null)
	{
		$template = $this->param['template'];
		$output = $this->param['output'];
		$data = \api\invoice::transform($id, $template, $output);
		$this->header->setMime($output);

		if ($output == 'pdf')
			$this->header->download(__('Invoice') . '.pdf');

		$this->output_header = $this->header->getHeader();
		$this->output_content = $data;
	}

	/**
	 * mails an invoice
	 *
	 * param['template'] : the template
	 * param[mail] : the mail
	 * param['message'] : evt. message
	 * param[subject] : the subject
	 *
	 * @param null $id
	 */
	function doMail($id = null){
        $subject = $this->param['subject'];
        $msg = $this->param['message'];
        $rec = $this->param['mail'];
        $template = $this->param['template'];

        \api\invoice::email($id, array($rec), $subject, $msg, $template);

        $html = $this->getTpl();

        $m = new \helper\layout\MessagePage('Congratz!',
            '<p>'.__('Your mail was sent.').'</p>');

        $html->add2content($m);

        $this->output_header = $this->header->getHeader();
        $this->output_content = $html->generate();
	}

    /**** AJAX FUNCTION *****/

	/**
	 * register invoice as payed
	 */
	function pay($id = null)
	{
		$input = new \helper\parser\Post('model\Base');
		$input->alterArray(function ($arr) {
			$arr['assAcc'] = (int)$arr['assAcc'];
			return $arr;
		});

		$input = $input->getObj();

		\api\invoice::bookkeep($id, $input->assAcc);

		$this->header->redirect('/invoice/view/' . (string)$id);
		$this->output_header = $this->header->getHeader();
		$this->output_content = '';
	}

	/**
	 * create new invoice, and update ole ones ;)
	 */
	function create()
	{
		$input = new \helper\parser\Post('model\finance\Invoice');

		$input->alterArray(function ($arr) {
			//checboxes and submits:
			if (isset($arr['vat']))
				$arr['vat'] = true;
			else
				$arr['vat'] = false;

			if (isset($arr['isPayed']))
				$arr['isPayed'] = true;
			else
				$arr['isPayed'] = false;

			if (isset($arr['send']))
				$arr['pendForSending'] = true;
			else
				$arr['pendForSending'] = false;

			//parsing the time
			if (isset($arr['Invoice']['IssueDate']))
				$arr['Invoice']['IssueDate'] =
					date('c', \DateTime::createFromFormat('d/m/Y', $arr['Invoice']['IssueDate'])->getTimestamp());
			else
				$arr['Invoice']['IssueDate'] = date('c');


			//note if the invoice is finished
			if (isset($arr['draft']))
				$arr['draft'] = true;
			else
				$arr['draft'] = false;

			//some quantaty and unitprice stuff
			foreach ($arr['Invoice']['InvoiceLine'] as &$il) {
				//casting to int, you can only sell natural numbers of stuff
				$il['InvoicedQuantity'] = (int)l::readNum($il['InvoicedQuantity']);
				$il['Price']['PriceAmount']['_content'] = l::readValuta($il['Price']['PriceAmount']['_content']);

				//doing the totals and taxes right
				unset($il['unitPrice']);
			}

			foreach ($arr['product'] as &$prod) {
				$prod['origAmount'] = l::readValuta($prod['origAmount']);
			}

			if (isset($arr['ExchangeRates']))
				foreach ($arr['ExchangeRates'] as &$er) {
					$er['calculationRate'] = l::readNum($er['calculationRate']);

				}

			//unsetting all the fields that are not a part of the model
			unset($arr['trash'], $arr['finished']);

			return $arr;
		});

		$obj = $input->getObj();
		if (isset($obj->_id))
			\api\invoice::update($obj);
		else
			$obj = \api\invoice::create($obj);

		$this->header->redirect('/invoice/view/' . (string)$obj->_id);

		$this->output_header = $this->header->getHeader();
		$this->output_content = '';
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

	/**
	 * Required functions
	 */

	function setup($done = false)
	{
		if ($done) {
			$this->getSiteAPI()->finishSetup('invoice');
			$this->header->redirect('/index');
		}

		$html = $this->getTpl();
		$html->appendContent(\helper\layout\Element::heading('Fakturering',
			'En lille introduktion'));
		$html->appendContent(new invoice\layout\finance\Setup());

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
}
