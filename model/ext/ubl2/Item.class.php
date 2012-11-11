<?php
/**
* documentation:
* search for UBL2.02 and their Party class
*/


namespace model\ext\ubl2;

class Item extends \model\AbstractModel{
	
	protected $_autoassign = array(
		'Description' => array('\model\ext\ubl2\field\Text', false),
		'PackQuantity' => array('\model\ext\ubl2\field\Quantity', false),
		'PackSizeNumeric' => array('\model\ext\ubl2\field\Numeric', false),
		'CatalogueIndicator' => array('\model\ext\ubl2\field\Indicator', false),
		'Name' => array('\model\ext\ubl2\field\Name', false),
		'HazardousRiskIndicator' => array('\model\ext\ubl2\field\Indicator', false),
		'AdditionalInformation' => array('\model\ext\ubl2\field\Text', false),
		'Keyword' => array('\model\ext\ubl2\field\Text', false),
		'BrandName' => array('\model\ext\ubl2\field\Name', false),
		'ModelName' => array('\model\ext\ubl2\field\Name', false),
		
		'BuyersItemIdentification' => array('\model\ext\ubl2\ItemIdentification', false),
		'SellersItemIdentification' => array('\model\ext\ubl2\ItemIdentification', false),
		'ManufacturersItemIdentification' => array('\model\ext\ubl2\ItemIdentification', false),
		'StandardItemIdentification' => array('\model\ext\ubl2\ItemIdentification', false),
		'CatalogueItemIdentification' => array('\model\ext\ubl2\ItemIdentification', false),
		'AdditionalItemIdentification' => array('\model\ext\ubl2\ItemIdentification', false),
		'CatalogueDocumentReference' => array('\model\ext\ubl2\DocumentReference', false),
		'ItemSpecificationDocumentReference' => array('\model\ext\ubl2\DocumentReference', false),
		'OriginCountry' => array('\model\ext\ubl2\Country', false),
		'CommodityClassification' => array('\model\ext\ubl2\CommodityClassification', false),
		'TransactionConditions' => array('\model\ext\ubl2\TransactionConditions', false), 
		'HazardousItem' => array('\model\ext\ubl2\HazardousItem', false),
		'ClassifiedTaxCategory' => array('\model\ext\ubl2\TaxCategory', false), 
		'AdditionalItemProperty' => array('\model\ext\ubl2\ItemProperty', false),
		'ManufacturerParty' => array('\model\ext\ubl2\Party', false), 
		'InformationContentProviderParty' => array('\model\ext\ubl2\Party', false),
		'OriginAddress' => array('\model\ext\ubl2\Address', false),
		'ItemInstance' => array('\model\ext\ubl2\ItemInstance', false),
	);
	
	protected $_namespace = array('cac',
		'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');

	protected $Description;//	Beskrivelse	Text	Ja	0..n
	protected $PackQuantity;//	PakkeMængde	Quantity	Ja	0..1
	protected $PackSizeNumeric;//	PakkeStørrelse	Numeric	Ja	0..1
	protected $CatalogueIndicator;//	KatalogIndikator	Indicator	Ja	0..1
	protected $Name;//	Navn	Name	Ja	1
	protected $HazardousRiskIndicator;//	FarlighedsIndikator	Indicator	Aftales	0..1
	protected $AdditionalInformation;//	SupplerendeInformation	Text	Ja	0..1
	protected $Keyword;//	Nøgleord	Text	Ja	0..n
	protected $BrandName;//	MærkeNavn	Name	Ja	0..n
	protected $ModelName;//	ModelNavn	Name	Ja	0..n

	protected $BuyersItemIdentification;//	KøbersVareIdentifikation	Ja	0..1	Bibliotek, 3.51	 
	protected $SellersItemIdentification;//	SælgersVareIdentifikation	Ja	1	Bibliotek, 3.51	 
	protected $ManufacturersItemIdentification;//	ProducentensVareIdentifikation	Ja	0..1	Bibliotek, 3.51	 
	protected $StandardItemIdentification;//	StandardVareIdentifikation	Ja	0..1	Bibliotek, 3.51	 
	protected $CatalogueItemIdentification;//	KatalogVareIdentifikation	Ja	0..1	Bibliotek, 3.51	 
	protected $AdditionalItemIdentification;//	SupplerendeVareIdentifikation	Ja	0..1	Bibliotek, 3.51	 
	protected $CatalogueDocumentReference;
	protected $ItemSpecificationDocumentReference;
	protected $OriginCountry;//	OprindelsesLand	Ja	0..1	Bibliotek, 3.24	 
	protected $CommodityClassification;//	VareTypeKlasifikation	Ja	0..n	Bibliotek, 3.18	 
	protected $TransactionConditions;//	HandelsBetingelser	Aftales	0..1	Bibliotek, 3.107	 
	protected $HazardousItem;//	FarligVare	Aftales	0..n	Bibliotek, 3.47	 
	protected $ClassifiedTaxCategory;//	KlasificeretAfgiftsKategori	Aftales	0..n	Bibliotek, 3.101	 
	protected $AdditionalItemProperty;//	SupplerendeVareEgenskaber	Ja	0..n	Bibliotek, 3.54	 
	protected $ManufacturerParty;//	Producent	Ja	0..1	Bibliotek, 3.70	 
	protected $InformationContentProviderParty;//	InformationsLeverandør	Aftales	0..1	Bibliotek, 3.70	 
	protected $OriginAddress;//	OprindelsesAdresse	Ja	0..1	Bibliotek, 3.1	 
	protected $ItemInstance;//
}

?>
