<?php
/**
 * dispatching Remote Procedure Calls
 */

namespace rpc;

class billing extends \core\rpc
{

	public $docs = array(
		'add' => 'adds invoice from the Invoice model. Returns the invoice',
		'addUBL' => 'takes an UBL invoice, and inserts it.',

		'pay' => 'takes invoice ID and sets state as payed'
	);


	/**
	 * requireLogin
	 */
	static public $requireLogin = true;

	/**
	 * adds a contact
	 */
	function create($bill)
	{
		try {
			$bill = $this->billPrepare($bill);
			$toRet = \api\billing::create($bill);

			$this->ret(array(
				'id' => (string)$toRet->_id,
				'success' => true,
			));
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		}
	}

	/**
	 * fetches an bill
	 */
	function get($id = null)
	{
		try {
			$bill = \api\billing::getOne($id);

			//TODO some preperations (is to move out in the data transform lib)
			$bill->paymentDate = date("c", $bill->paymentDate);
			$bill->_id = (string)$bill->_id;

			$this->ret($bill->toArray());
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		}
	}

	/**
	 * updates a bill
	 *
	 * @param $newBill \model\finance\Bill
	 */
	function update($newBill)
	{
		try {
			//fetch object
			$newBill = $this->billPrepare($newBill);
			//update the stuff
			\api\billing::update($newBill);
			//return success
			$this->ret(array('success', true));
		} catch (\exception\UserException $e) {
			$this->throwException($e->getMessage());
		}
	}

	private function billPrepare($b)
	{
		//some sanitizing:
		if (isset($b['paymentDate']))
			$b['paymentDate'] = strtotime($b['paymentDate']);

		$b = \helper\model\Arr::toModel($b, '\model\finance\Bill');
		return $b;
	}
}

?>
