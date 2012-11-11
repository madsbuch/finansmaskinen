<?php
/**
* setup data for a dansih solitair company
*/

namespace app\accounting\setups;

class DkkSolitaire {
	//default vatcodes
	public static $vatCodes = array(
		array(
			'code' => 'U25',
			'name' => 'Udgående moms',
			'percentage' => 25.0,
			'account' => 14261,
			'counterAccount' => null,
			'net' => true,
		),
		array(
			'code' => 'I25',
			'name' => 'Indgående moms',
			'percentage' => 25.0,
			'account' => 14262,
			'counterAccount' => null,
			'net' => true,
		),
		array(
			'code' => 'REP',
			'name' => 'Repræsentation',
			'percentage' => 5.26,
			'account' => 14261,
			'counterAccount' => null,
			'net' => false,
		),
		array(
			'code' => 'HREP',
			'name' => 'Repræsentation - hotelbesøg',
			'percentage' => 11.11,
			'account' => 14261,
			'counterAccount' => null,
			'net' => false,
		),
	);
	//default accounts
	public static $accounts = array(
		//expenses accounts
		array(
			'name' => 'Vareforbrug',
			'code' => 2100,
			'vatCode' => 'U25',
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Salgsfremmende omkostninger',
			'code' => 3100,
			'vatCode' => 'U25',
			'type' => 3,//expenses
			'allowPayments' => false
		),

		array(
			'name' => 'Salgsfragt',
			'code' => 3600,
			'vatCode' => 'U25',
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Tab på tilgodehavender',
			'code' => 3800,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Øvrige omkostninger',
			'code' => 3900,
			'vatCode' => 'U25',
			'type' => 3,//expenses
			'allowPayments' => false
		),
		
		//some income accounts
		array(
			'name' => 'Varesalg',
			'code' => 1100,
			'vatCode' => 'I25',
			'type' => 4,
			'allowPayments' => false
		),
		
		//asstes
		array(
			'name' => 'Varelager',
			'code' => 12110,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Kasse',
			'code' => 12310,
			'type' => 1,
			'allowPayments' => true
		),
		array(
			'name' => 'Bank',
			'code' => 12320,
			'type' => 1,
			'allowPayments' => true
		),
		array(
			'name' => 'Købsmoms',
			'code' => 14261,
			'type' => 1,
			'allowPayments' => false
		),
		
		//and last, liabillity
		array(
			'name' => 'Egenkapital',
			'code' => 13110,
			'type' => 2,
			'allowPayments' => false
		),
		array(
			'name' => 'Privatforbrug',
			'code' => 13111,
			'type' => 2,
			'allowPayments' => false
		),
		array(
			'name' => 'Momsafregning',
			'code' => 14260,
			'type' => 2,
			'allowPayments' => false
		),
		array(
			'name' => 'Salgsmoms',
			'code' => 14262,
			'type' => 2,
			'allowPayments' => false
		),
	);
	
	//default quick transactions
	public static $quickTransaction = array(
		array(
			'name' => 'Indskyd penge'
			//and so on...
		)
	);
}

?>
