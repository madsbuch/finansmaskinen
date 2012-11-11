<?xml version="1.0" encoding="UTF-8"?>
<!--
******************************************************************************************************************

		OIOUBL Instance Documentation	

		title= CreditNoteHTML.xsl
		replaces= creditnote.xsl	
		publisher= "IT og Telestyrelsen"
		Creator= Finn Christensen and Charlotte Dahl Skovhus
		created= 2006-12-29
		modified= 2008-01-22
		issued= 2008-01-22
		conformsTo= UBL-CreditNote-2.0.xsd
		description= "Stylesheet for displaying a OIOUBL-2.01 CreditNote"
		rights= "It can be used following the Common Creative Licence"
		
		all terms derived from http://dublincore.org/documents/dcmi-terms/

		For more information, see www.oioubl.dk	or email oioubl@itst.dk
		
******************************************************************************************************************
-->
<xsl:stylesheet version="1.0" 

        xmlns:xsl  = "http://www.w3.org/1999/XSL/Transform" 
        xmlns:n1   = "urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2" 
        xmlns:cac  = "urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" 
        xmlns:cbc  = "urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" 
        xmlns:ccts = "urn:oasis:names:specification:ubl:schema:xsd:CoreComponentParameters-2" 
        xmlns:sdt  = "urn:oasis:names:specification:ubl:schema:xsd:SpecializedDatatypes-2" 
        xmlns:udt  = "urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2"
                                      exclude-result-prefixes="n1 cac cbc ccts sdt udt">


	<xsl:include href="OIOUBL_CommonTemplates.xsl"/>
	<xsl:output method="html" doctype-public="-//W3C//DTD HTML 4.01 Transitional//EN" doctype-system="http://www.w3.org/TR/html4/loose.dtd" indent="yes"/>
	<xsl:strip-space elements="*"/>
	<xsl:template match="/">
		<xsl:apply-templates/>
	</xsl:template>

	<xsl:template match="n1:CreditNote">

		<!-- Start HTML -->
		<html>
			<head>
				<link rel="Stylesheet" type="text/css" href="OIOUBL.css"></link>
				<title>OIOUBL-2.01 dokumentudskrivning version 1.0 release <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ReleaseDate']"/></title>
			</head>
			<body>
				<!-- Start på kreditnotahovedet -->
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td colspan="4">
							
						</td>
					</tr>
				</table>
				<br/>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td>
							<!-- indsætter header -->
							<h3>
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OIOUBLCN']"/>
								<xsl:if test="cbc:CopyIndicator ='true'"><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='CopyIndicator']"/></xsl:if>
							</h3>
						</td>
						<td/>
						<td/>
						<td/>
					</tr>
					<tr>
						<td colspan="5">
							<hr size="5"/>
						</td>
					</tr>

					<tr>
						<td width="30%">
							<!-- indsætter kreditor -->
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingSupplierInv']"/></b>
							<br/>
							<xsl:apply-templates select="cac:AccountingSupplierParty"/>
						</td>
						<td width="30%">
							<!-- indsætter kontaktoplysninger -->
							<xsl:if test="cac:AccountingSupplierParty/cac:Party/cac:Contact !=''">
								<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/></b><br/>
								<xsl:apply-templates select="cac:AccountingSupplierParty/cac:Party" mode="accsupcontact"/>
							</xsl:if>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<hr size="2"/>
						</td>
					</tr>
					<tr>
						<td width="30%">
							<!-- indsætter debitor -->
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingCustomerInv']"/></b>
							<br/>
							<xsl:apply-templates select="cac:AccountingCustomerParty"/>
							<xsl:if test="cbc:AccountingCost !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AccountingCost']"/>&#160;<xsl:value-of select="cbc:AccountingCost"/>
							</xsl:if>
							<br/>
						</td>
						<td width="30%">
							<!-- indsætter kontaktoplysninger -->
							<xsl:if test="cac:AccountingCustomerParty/cac:Party/cac:Contact !=''">
								<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/></b>
								<br/>
								<xsl:apply-templates select="cac:AccountingCustomerParty/cac:Party" mode="acccuscontact"/>
							</xsl:if>
						</td>
							<!-- indsætter eventuel faktureringsadresse -->
							<xsl:if test="cac:PayeeParty !=''">
								<xsl:if test="cac:PayeeParty/cac:PartyIdentification/cbc:ID != cac:AccountingCustomerParty/cac:Party/cac:PartyIdentification/cbc:ID">
									<xsl:if test="cac:PayeeParty/cac:PartyName/cbc:Name != cac:AccountingCustomerParty/cac:Party/cac:PartyName/cbc:Name">
										<td width="30%">
											<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PayeeParty']"/></b>
											<br/>
											<xsl:apply-templates select="cac:PayeeParty"/>            
										</td>
									</xsl:if>
								</xsl:if>
							</xsl:if>
					</tr>
				</table>
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td colspan="5">
							<hr size="2"/>
						</td>
					</tr>
					<tr>
						<td width="20%">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='CreditNoteID']"/>&#160;</b>
							<!-- indsætter Kreditnotanummer -->
							<xsl:apply-templates select="cbc:ID"/>
						</td>
						<td width="20%">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='IssueDate']"/>&#160;</b>
							<!-- indsætter kreditnota dato -->
							<xsl:apply-templates select="cbc:IssueDate"/>
						</td>
						<td width="20%">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='InvoiceID']"/>&#160;</b>
							<!-- indsætter Sælgers Fakturanr  -->
							<xsl:apply-templates select="cac:BillingReference/cac:InvoiceDocumentReference/cbc:ID"/>
						</td>
						<td width="20%">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='InvoiceIssueDate']"/>&#160;</b>
							<!-- indsætter Sælgers Fakturadato -->
							<xsl:apply-templates select="cac:BillingReference/cac:InvoiceDocumentReference/cbc:IssueDate"/>
						</td>
						<td width="20%">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderReferenceID']"/>&#160;</b>
							<!-- indsætter Ordrenr  -->
							<xsl:apply-templates select="cac:OrderReference/cbc:ID"/>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<hr size="2"/>
						</td>
					</tr>
				</table>
				<br/>
				<!-- Slut på kreditnotahovedet -->
				
				<!--Start kreditnotalinje-->
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr class="UBLCreditnoteLineHeader">
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='LineID']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='SellersItemIdentification']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ItemName']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Quantity']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='QuantityUnitCode']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PriceUnit']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='TaxScheme']"/></b>
						</td>
						<td align="right">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='LineExtensionAmountLine']"/></b>
						</td>
					</tr>
					<xsl:apply-templates select="cac:CreditNoteLine"/>
				</table>
				<!--Slut kreditnotalinje-->
	
				<!--Start afgifter og totaler-->
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td colspan="8">
							<hr size="2"/>
						</td>
					</tr>
						<!-- Linjesum -->
						<xsl:apply-templates select="cac:LegalMonetaryTotal" mode="LineTotal"/>
						<!-- Afgifter på header -->
						<xsl:apply-templates select="cac:TaxTotal" mode="afgift"/>
						<!-- Rabat og gebyr på header -->
						<xsl:apply-templates select="cac:AllowanceCharge" mode="total"/>
						<!-- Moms  -->
						<xsl:apply-templates select="cac:TaxTotal" mode="moms"/>
						<!-- Kreditnotatotal  -->
						<xsl:apply-templates select="cac:LegalMonetaryTotal" mode="TotalKreditNota"/>
					<tr>
						<td colspan="8">
							<hr size="5"/>
						</td>
					</tr>
				</table>
				<!--Slut afgifter og totaler-->
				
				<!-- Start på fritekst og referencer-->
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td>
						<xsl:if test="cac:InvoicePeriod !=''">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='InvoicePeriod']"/></b>&#160;<xsl:apply-templates select="cac:InvoicePeriod"/><br/>
						</xsl:if>
						<xsl:if test="cac:DiscrepancyResponse/cbc:ReferenceID !=''">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ReferenceID']"/></b>&#160;
							<xsl:apply-templates select="cac:DiscrepancyResponse/cbc:ReferenceID"/>&#160;-&#160;
							<xsl:apply-templates select="cac:DiscrepancyResponse/cbc:Description"/><br/>
						</xsl:if>
						<xsl:if test="cac:BillingReference !=''">
							<xsl:apply-templates select="cac:BillingReference"/>
						</xsl:if>
						<xsl:if test="cac:OrderReference/cac:DocumentReference !=''">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OrderDocumentReference']"/></b>&#160;<xsl:apply-templates select="cac:OrderReference" mode="reference"/><br/>
						</xsl:if>
						<xsl:if test="cbc:Note[.!='']">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Notes']"/></b>&#160;<xsl:apply-templates select="cbc:Note"/><br/>
						</xsl:if>
						<xsl:if test="cac:ContractDocumentReference/cbc:ID !=''">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ContractDocumentReference']"/></b>&#160;<xsl:apply-templates select="cac:ContractDocumentReference"/><br/>
						</xsl:if>
						<xsl:if test="cac:AdditionalDocumentReference/cbc:ID !=''">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='AdditionalDocumentReferenceID']"/></b>&#160;<xsl:apply-templates select="cac:AdditionalDocumentReference"/>
						</xsl:if>

						<xsl:apply-templates select="cac:LegalMonetaryTotal" mode="supp"/>
						</td>
					</tr>
				</table>	
				<!-- Slut på fritekst og referencer-->
								
				<!-- Start på OIOUBL footer -->
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td colspan="3">
							<hr size="2"/>
						</td>
					</tr>
					<tr>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OIOUBLDoc']"/></b>
							<br/>
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='VersionID']"/>&#160;<xsl:value-of select="cbc:UBLVersionID"/>
							<br/>
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='CustomizationID']"/>&#160;<xsl:value-of select="cbc:CustomizationID"/>
							<br/>
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ProfileID']"/>&#160;<xsl:value-of select="cbc:ProfileID"/>
							<br/>
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ID']"/>&#160;<xsl:value-of select="cbc:ID"/>
							<br/>
							<xsl:if test="cbc:UUID !=''">
							<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='UUID']"/>&#160;<xsl:value-of select="cbc:UUID"/>
							</xsl:if>
							<br/>
							<xsl:if test="cbc:DocumentCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='DocumentCurrencyCode']"/>&#160;<xsl:value-of select="cbc:DocumentCurrencyCode"/>
							<br/>
							</xsl:if>							
							<xsl:if test="cbc:TaxCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='TaxCurrencyCode']"/>&#160;<xsl:value-of select="cbc:TaxCurrencyCode"/>
							<br/>	
							</xsl:if>
							<xsl:if test="cbc:PricingCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PricingCurrencyCode']"/>&#160;<xsl:value-of select="cbc:PricingCurrencyCode"/>
							<br/>
							</xsl:if>
							<xsl:if test="cbc:PaymentCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PaymentCurrencyCode']"/>&#160;<xsl:value-of select="cbc:PaymentCurrencyCode"/>
							<br/>
							</xsl:if>
							<xsl:if test="cbc:PaymentAlternativeCurrencyCode !=''">
								<xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='PaymentAlternativeCurrencyCode']"/>&#160;<xsl:value-of select="cbc:PaymentAlternativeCurrencyCode"/>
							<br/>
							</xsl:if>
							
						</td>
						<xsl:if test="cac:Signature !=''">
							<td>
								<xsl:apply-templates select="cac:Signature"/>
							</td>
						</xsl:if>
					</tr>
				</table>
				<!-- Slut på OIOUBL footer -->
			</body>
		</html>
	</xsl:template>
	
</xsl:stylesheet>
