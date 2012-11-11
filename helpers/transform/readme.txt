transformation of invoices:

They work like channels, or pipes

so, the system consists of two parts:

    some interfaces:
    	interfaces are for two things: eg. XML, there is no need to regenerate
    	a DOM object from a string, if we know that input data is of XML, then
    	we define a interface allowing passing of domstructure
    	
		they are also used to define output of a processor
		
    processors, that takes some input, complying with some interfacing, and returns some output

example:

$pdf = \helper\transform\XML::create($invoiceModel)->XLST(invToHtml.xls)->PDF('html')->generate();
$html = \helper\transform\Model::create($model)->Savant('tplfile.tpl.php')->generate();


core transformation:

XML:
	model -> XML
JSON:
	model -> JSON
Model
	XML | JSON -> Model

other:

PDF(mode)
	mode -> pdf (cache object) (eks: HTML -> PDF)

XSLT(transformationDocument, asString = false)
	XML -> XML (transformed)

Savant(savantDocument)
	model -> str
