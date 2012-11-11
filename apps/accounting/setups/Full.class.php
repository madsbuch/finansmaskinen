<?php
/**
* setup data for a dansih solitair company
*/

class DkkSolitair {
	//default vatcodes
	public $vatCodes = array(
		
	);
	public $accounts = array(
		//expenses accounts
		array(
			'name' => 'Vareforbrug',
			'code' => 2100,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Salgsfremmende omkostninger',
			'code' => 3100,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Lokaleomkostninger',
			'code' => 3200,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Kassedifferencer',
			'code' => 3300,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Bilers driftsomkostninger',
			'code' => 3400,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Salgsfragt',
			'code' => 3600,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Tab på tilgodehavender',
			'code' => 3800,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Øvrige omkostninger',
			'code' => 3900,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Lønafregning',
			'code' => 4100,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'ATP-bidrag',
			'code' => 4200,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Afskrivninger på biler',
			'code' => 5100,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Afskrivninger på inventar',
			'code' => 5200,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Renteomkostninger',
			'code' => 7100,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		array(
			'name' => 'Kontantrabat til kunder',
			'code' => 7200,
			'vatCode' => 25.0,
			'type' => 3,//expenses
			'allowPayments' => false
		),
		
		//some income accounts
		array(
			'name' => 'Varesalg',
			'code' => 1100,
			'vatCode' => 25.0,
			'type' => 4,
			'allowPayments' => false
		),
		array(
			'name' => 'Renteindtægter',
			'code' => 6100,
			'vatCode' => 25.0,
			'type' => 4,
			'allowPayments' => false
		),
		array(
			'name' => 'Kontantrabat fra leverandører',
			'code' => 6200,
			'vatCode' => 25.0,
			'type' => 4,
			'allowPayments' => false
		),
		
		//asstes
		array(
			'name' => 'Biler',
			'code' => 11120,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Akk. afskrivninger på biler',
			'code' => 11121,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Inventar',
			'code' => 11130,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Akk. afskrivninger på inventar',
			'code' => 11131,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Varelager',
			'code' => 12110,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Varedebitorer',
			'code' => 12210,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Periodeafgrænsningsposter',
			'code' => 12230,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Kasse',
			'code' => 12310,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => true
		),
		array(
			'name' => 'Bank',
			'code' => 12320,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => true
		),
		array(
			'name' => 'Købsmoms',
			'code' => 14261,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		
		//and last, liabillity
		array(
			'name' => 'Egenkapital',
			'code' => 13110,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Privatforbrug',
			'code' => 13111,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Kassekredit',
			'code' => 14210,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Varekreditorer',
			'code' => 14220,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Skyldig ATP',
			'code' => 14230,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Skyldig AM-bidrag',
			'code' => 14240,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Skyldig SP-bidrag',
			'code' => 14245,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Skyldig A-skat',
			'code' => 14250,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Momsafregning',
			'code' => 14260,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Salgsmoms',
			'code' => 14262,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
		array(
			'name' => 'Andre kreditorer',
			'code' => 14290,
			'vatCode' => 25.0,
			'type' => 1,
			'allowPayments' => false
		),
	);
	
}

?>
