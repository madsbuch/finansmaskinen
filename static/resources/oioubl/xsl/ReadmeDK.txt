ReadmeDK.txt
Revideret: 12. maj 2011 af Charlotte Dahl Skovhus, mySupply ApS.


OIOUBL 2.02 HTML Visningsstylesheets.
-------------------------------------------------------


1.0 Anvendelse
--------------
Anvendes til at præsentere OIOUBL dokumenter i HTML format.

Dokumentstylesheets:

OrderHTML2008-01-22.xsl
OrderResponseSimpleHTML2008-01-22.xsl
OrderResponseHTML2008-01-22.xsl
OrderChangeHTML2008-01-22.xsl
OrderCancellationHTML2008-01-22.xsl
InvoiceHTML2008-01-22.xsl
CreditNoteHTML2008-01-22.xsl
ReminderHTML2008-01-22.xsl
StatementHTML2008-01-24.xsl
ApplicationResponseHTML2008-01-22.xsl

For hvert OIOUBL dokument er der oprettet et HTML visningsstylesheet. 
Alle dokumentstylesheets refererer til OIOUBL_CommonTemplate.xsl 

Tværgående stylesheet:
- OIOUBL_CommonTemplates.xsl

Overskrifter / fælles-værdier:
- OIOUBL_Headlines.xml

Styling:
- OIOUBL.css

Eksempel på brug:
- msxsl.exe Examples/OIOUBL_Invoice_v2p1.xml InvoiceHTML.xsl -o output.html -xe



2.0 Forudsætninger og installation
----------------------------------
Det forudsættes at OIOUBL instanserne er valideret korrekt (både xsd og schematron).


3.0 Release Notes
-----------------
a.  Der er ikke oprettet stylesheets til alle OIOUBL dokumenter, således ikke til katalogdokumenterne
b.  Ikke alle felter vises p.t.
c.  Alle filer er omnavngivet, således at dato ikke længere indgår i filnavne.
d.  TITLE i output HTML indeholder nu release-dato for hele pakken.
    Hver enkelt fil indeholder stadig information om sidste indholdsmæssige rettelse.


4.0 Revisionslog
-----------------
22.01.2008: Version uploadet til www.oioubl.info
12.05.2011: CommonTemplate opdateret så ydelsesmodtageren vises på linjen for Invoice og Creditnote


5.0 Rapportering af fejl og mangler etc.
----------------------------------------
Information om fejl, mangler, og andet relevant, modtages meget gerne på følgende mailadresse:
    oioubl@itst.dk

På forhånd tak!
