<?php
/**
* documentation:
* search for UBL2.02
*/


namespace model\ext\ubl2;

class Address extends \model\AbstractModel{
	
	protected $_autoassign = array(
		'ID' => array('\model\ext\ubl2\field\Identifier', false),
		'AddressTypeCode' => array('\model\ext\ubl2\field\Code', false),
		'AddressFormatCode' => array('\model\ext\ubl2\field\Code', false),
		'Postbox' => array('\model\ext\ubl2\field\Text', false),
		'Floor' => array('\model\ext\ubl2\field\Text', false),
		'Room' => array('\model\ext\ubl2\field\Text', false),
		'StreetName' => array('\model\ext\ubl2\field\Name', false),
		'AdditionalStreetName' => array('\model\ext\ubl2\field\Name', false),
		'BuildingName' => array('\model\ext\ubl2\field\Name', false),
		'BuildingNumber' => array('\model\ext\ubl2\field\Text', false),
		'InhouseMail' => array('\model\ext\ubl2\field\Text', false),
		'Department' => array('\model\ext\ubl2\field\Text', false),
		'MarkAttention' => array('\model\ext\ubl2\field\Text', false),
		'MarkCare' => array('\model\ext\ubl2\field\Text', false),
		'PlotIdentification' => array('\model\ext\ubl2\field\Text', false),
		'CitySubdivisionName' => array('\model\ext\ubl2\field\Name', false),
		'CityName' => array('\model\ext\ubl2\field\Name', false),
		'PostalZone' => array('\model\ext\ubl2\field\Text', false),
		'CountrySubentity' => array('\model\ext\ubl2\field\Text', false),
		'CountrySubentityCode' => array('\model\ext\ubl2\field\Code', false),
		'Region' => array('\model\ext\ubl2\field\Text', false),
		'District' => array('\model\ext\ubl2\field\Text', false),
		'BlockName' => array('\model\ext\ubl2\field\Name', false),
		'TimezoneOffset' => array('\model\ext\ubl2\field\Timezone', false),
		
		'AddressLine' => array('\model\ext\ubl2\AddressLine', false),
		'Country' => array('\model\ext\ubl2\Country', false),
	);
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

	protected $ID;//	ID	Identifier	Ja	0..1
	protected $AddressTypeCode;//	TypeKode	Code	Aftales	0..1
	protected $AddressFormatCode;//	FormatKode	Code	Ja	1
	protected $Postbox;//	Postboks	Text	Ja	0..1
	protected $Floor;//	Etage	Text	Ja	0..1
	protected $Room;//	Rum	Text	Ja	0..1
	protected $StreetName;//	Vejnavn	Name	Ja	0..1
	protected $AdditionalStreetName;//	VejAdresseringsNavn	Name	Ja	0..1
	protected $BuildingName; //Lokalitet	Name	Ja	0..1
	protected $BuildingNumber;//	Husnummer	Text	Ja	0..1
	protected $InhouseMail;//	Dueslag	Text	Aftales	0..1
	protected $Department;//	Afdeling	Text	Ja	0..1
	protected $MarkAttention;//	Attention	Text	Ja	0..1
	protected $MarkCare;//	C/O	Text	Ja	0..1
	protected $PlotIdentification;//	GrundIdentifikation	Text	Aftales	0..1
	protected $CitySubdivisionName;//	ByDelsNavn	Name	Aftales	0..1
	protected $CityName;//	ByNavn	Name	Ja	0..1
	protected $PostalZone;//	Postnummer	Text	Ja	0..1
	protected $CountrySubentity;//	Landsdel	Text	Ja	0..1
	protected $CountrySubentityCode;//	LandsdelsKode	Code	Ja	0..1
	protected $Region;//	Region	Text	Ja	0..1
	protected $District;//	Distrikt	Text	Ja	0..1
	protected $BlockName;//	Blok	Felt
	protected $TimezoneOffset;//
	
	protected $AddressLine;//	Adresse linje	Ja	0..n	Bibliotek, 3.2	 
	protected $Country;//	Land	Ja	0..1	Bibliotek, 3.24	 
	protected $LocationCoordinate;//	Adresse Punkt	Klasse
}

?>
