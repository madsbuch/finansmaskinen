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
			'name' => 'Udgående (Salg)',
			'percentage' => 25.0,
			'deductionPercentage' => 25,
			'account' => 14262,
			'contraAccount' => null,
			'net' => true,
			'taxcatagoryID' => 'StandardRated',
			'type' => 1
		),
		array(
			'code' => 'I25',
			'name' => 'Indgående (Køb)',
			'percentage' => 25.0,
			'deductionPercentage' => 25,
			'account' => 14261,
			'contraAccount' => null,
			'net' => true,
			'taxcatagoryID' => 'StandardRated',
			'type' => 2
		),
		array(
			'code' => 'REP',
			'name' => 'Repræsentation',
			'percentage' => 25,
			'deductionPercentage' => 6.25,
			'account' => 14261,
			'contraAccount' => null,
			'net' => true,
			'type' => 2
		),
		array(
			'code' => 'HREP',
			'name' => 'Repræsentation - hotelbesøg',
			'percentage' => 25,
			'deductionPercentage' => 12.5,
			'account' => 14261,
			'contraAccount' => null,
			'net' => true,
			'type' => 2
		),
	);
	//default accounts
	public static $accounts = array(
		//expenses accounts
		array(
			'name' => 'Vareforbrug',
			'code' => 2100,
			'vatCode' => 'I25',
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Salgsfremmende omkostninger',
			'code' => 3100,
			'vatCode' => 'I25',
			'type' => 3,//expenses
			'allowPayments' => false
		),

		array(
			'name' => 'Salgsfragt',
			'code' => 3600,
			'vatCode' => 'I25',
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
			'vatCode' => 'I25',
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'momsfrit',
			'code' => 3999,
			'vatCode' => null,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		
		//some income accounts
		array(
			'name' => 'Varesalg',
			'code' => 1100,
			'vatCode' => 'U25',
			'type' => 4,
			'allowPayments' => false
		),
		
		array(
			'name' => 'Salg af ydelser',
			'code' => 1200,
			'vatCode' => 'U25',
			'type' => 4,
			'allowPayments' => false
		),
		array(
			'name' => 'momsfrit',
			'code' => 1999,
			'vatCode' => null,
			'type' => 4,
			'allowPayments' => false
		),
		
		
		//asstes
		array(
			'name' => 'Varelager',
			'code' => 12110,
			'type' => 1,
			'defaultReflection' => 13110,
			'allowPayments' => false
		),
		array(
			'name' => 'Kasse',
			'code' => 12310,
			'type' => 1,
			'defaultReflection' => 13110,
			'allowPayments' => true
		),
		array(
			'name' => 'Bank',
			'code' => 12320,
			'type' => 1,
			'defaultReflection' => 13110,
			'allowPayments' => true
		),
		array(
			'name' => 'Købsmoms',
			'code' => 14261,
			'type' => 1,
			'allowPayments' => false
		),
		
		//and last, liabillity
		
		//equities
		array(
			'name' => 'Egenkapital',
			'code' => 13110,
			'type' => 2,
			'allowPayments' => false,
			'isEquity' => true
		),
		
		//and some stuff
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
	
	/**
	* initial products catagories
	*/
	public static $productCatagories = array(
		array(
			'name' => 'Ydelser',
			'description' => 'Ydelser der sælges, og pålægges moms',
			'accountInclVat' => 1200,
			'accountExclVat' => 1999,

			'expenseAccountInclVat' => 1200,
			'expenseAccountExclVat' => 1999,

			'accountLiability' => 13110
		),
		array(
			'name' => 'Produkter',
			'description' => 'Produkter der sælges, og pålægges moms',
			'accountInclVat' => 1100,
			'accountExclVat' => 1999,

			'expenseAccountInclVat' => 1100,
			'expenseAccountExclVat' => 1999,

			'accountLiability' => 13110,
			'stockAccount' => 12110
		)
	);

	/**
	 * @var array initial settings.
	 */
	public static $settings = array(
		'vatSettlementAccount' => 14260
	);
}

?>
