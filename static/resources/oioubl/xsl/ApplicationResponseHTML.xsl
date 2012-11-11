<?xml version="1.0" encoding="UTF-8"?>
<!--
******************************************************************************************************************

		OIOUBL Instance Documentation	

		title= ApplicationResponseHTML.xsl
		replaces= applicationresponse.xml	
		publisher= "IT og Telestyrelsen"
		Creator= Finn Christensen and Charlotte Dahl Skovhus
		created= 2006-12-29
		modified= 2008-01-22
		issued= 2008-01-22
		conformsTo= UBL-ApplicationResponse-2.0.xsd
		description= "Stylesheet for displaying a OIOUBL-2.01 ApplicationResponse"
		rights= "It can be used following the Common Creative Licence"
		
		all terms derived from http://dublincore.org/documents/dcmi-terms/

		For more information, see www.oioubl.dk	or email oioubl@itst.dk
		
******************************************************************************************************************
-->
<xsl:stylesheet version="1.0" 

        xmlns:xsl  = "http://www.w3.org/1999/XSL/Transform" 
        xmlns:n1   = "urn:oasis:names:specification:ubl:schema:xsd:ApplicationResponse-2" 
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

	<xsl:template match="n1:ApplicationResponse">

		<!-- Start HTML -->
		<html>
			<head>
				<link rel="Stylesheet" type="text/css" href="OIOUBL.css"></link>
				<title>OIOUBL-2.01 dokumentudskrivning version 1.0 release <xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ReleaseDate']"/></title>
			</head>
			<body>
				<!-- Start på applicationresponse hovedet -->
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
							<h3><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='OIOUBLAR']"/></h3>
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
						<td>
							<!-- indsætter afsenderadressen -->
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='SenderParty']"/></b>
							<br/>
							<xsl:apply-templates select="cac:SenderParty"/>
						</td>
						<td colspan="2">
							<!-- indsætter kontaktoplysninger -->
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/></b>
							<br/>
							<xsl:apply-templates select="cac:SenderParty" mode="sendercontact"/>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<hr size="2"/>
						</td>
					</tr>
					<tr>
						<td>
							<!-- indsætter modtageradressen -->
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ReceiverParty']"/></b>
							<br/>
							<xsl:apply-templates select="cac:ReceiverParty"/>            
						</td>
						<td>
							<!-- indsætter kontaktoplysninger -->
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Contact']"/></b>
							<br/>
							<xsl:apply-templates select="cac:ReceiverParty" mode="receivercontact"/>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<hr size="2"/>
						</td>
					</tr>
					<tr>
						<td width="26%">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ApplicationResponseID']"/>&#160;</b>
							<!-- indsætterApplicationResponse nummer -->
							<xsl:value-of select="cbc:ID"/>
						</td>
						<td width="27%">
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='IssueDate']"/>&#160;</b>
							<!-- indsætter dato -->
							<xsl:value-of select="cbc:IssueDate"/>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<hr size="2"/>
						</td>
					</tr>
				</table>
				<br/>
				<!-- Slut på applicationresponshovedet -->
				
				<!--Start responsårsag-->
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr class="UBLApplicationResponseReason">
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ReferenceIDAR']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ResponseCode']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ResponseDescription']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='DocumentTypeCode']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='DocumentReferenceID']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='IssueDate']"/></b>
						</td>
					</tr>
					<tr>
						<td>
							<xsl:value-of select="cac:DocumentResponse/cac:Response/cbc:ReferenceID"/>
						</td>
						<td>
							<xsl:value-of select="cac:DocumentResponse/cac:Response/cbc:ResponseCode"/>
						</td>
						<td>
							<xsl:apply-templates select="cac:DocumentResponse/cac:Response/cbc:Description"/>
						</td>
						<td>
							<xsl:apply-templates select="cac:DocumentResponse/cac:DocumentReference/cbc:DocumentTypeCode"/>
						</td>
						<td>
							<xsl:apply-templates select="cac:DocumentResponse/cac:DocumentReference/cbc:ID"/>
						</td>
						<td>
							<xsl:apply-templates select="cac:DocumentResponse/cac:DocumentReference/cbc:IssueDate"/>
						</td>
					</tr>
				</table>
				<!--Slut responsårsag-->
	

				<!--Start linjerespons-->
				<table border="0" width="100%" cellspacing="0" cellpadding="2">
					<tr>
						<td colspan="4">
							<hr size="2"/>
						</td>
					</tr>
					<xsl:if test="cac:DocumentResponse/cac:LineResponse/cac:LineReference/cbc:LineID !=''">
						<tr>
							<td colspan="2">
								<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='LineResponse']"/></b>
							</td>
						</tr>
					</xsl:if>
					<tr>	
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='LineID']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ReferenceIDAR']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ResponseCode']"/></b>
						</td>
						<td>
							<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='ResponseDescription']"/></b>
						</td>
					</tr>
					<tr>	
						<td>
							<xsl:value-of select="cac:DocumentResponse/cac:LineResponse/cac:LineReference/cbc:LineID"/>
						</td>
						<td>
							<xsl:value-of select="cac:DocumentResponse/cac:LineResponse/cac:Response/cbc:ReferenceID"/>
						</td>
						<td>
							<xsl:apply-templates select="cac:DocumentResponse/cac:LineResponse/cac:Response/cbc:ResponseCode"/>
						</td>
						<td>
							<xsl:apply-templates select="cac:DocumentResponse/cac:LineResponse/cac:Response/cbc:Description"/>
						</td>
					</tr>
				</table>
				<!--Slut linjerespons-->
				
				<!--Start note og dokumentrefernce-->
				<br/>
				<xsl:if test="cbc:Note !='' or cac:DocumentResponse/cac:DocumentReference !=''">
					<table border="0" width="100%" cellspacing="0" cellpadding="2">
						<tr>
							<td colspan="3">
								<hr size="2"/>
							</td>
						</tr>
						<tr>
							<td>
								<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='Notes']"/></b>&#160;<xsl:apply-templates select="cbc:Note"/>
							</td>
						</tr>
						<xsl:if test="cac:DocumentResponse/cac:DocumentReference !=''">
							<tr>
								<td>
									<b><xsl:value-of select="$moduleDoc/module/document-merge/g-funcs/g[@name='DocumentReference']"/></b>&#160;<xsl:apply-templates select="cac:DocumentResponse/cac:DocumentReference"/>
								</td>
							</tr>
						</xsl:if>
					</table>
				</xsl:if>
				<!--Slut note og dokumentrefernce-->
				
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
