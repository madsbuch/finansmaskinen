<?php
$form = $html->blockHelper("form", array("method" => "post"));
$form->addContent($html->paragraph("For at oprette en bruger, udfyld alle felterne nedenfor"));

//Først vil vi have brugeroplysninger
$form->addContent($html->header("Brugeroplysninger", 3));

//navn
$form->addLabel('name', 'Navn');
$form->addInput(array("type" => "text", "name" => "name", "class" => "form", "style" => "width:300px;"));
$form->addContent($html->nl());
//mail
$form->addLabel("mail", "Mail");
$form->addInput(array("type" => "mail", "name" => "mail", "class" => "form", "style" => "width:300px;"));
$form->addContent($html->nl());
//gentage, så den er rigtig ;)
$form->addLabel("remail", "Gentag mail");
$form->addInput(array("type" => "text", "name" => "remail", "class" => "form", "style" => "width:300px;"));

//koden autogeneres, så den er sikker fra start. Der gives dog mulighed for at ændre den

//så skal der nogle virksomhedsoplysninger til
$form->addContent($html->header("Virksomhedsoplysninger", 3));

//CVR med mulighed for udtræk fra CVR database
$form->addLabel("cvr", "CVR");
$form->addInput(array("type" => "text", "name" => "cvr", "class" => "form", "style" => "width:225px;"));
$form->addInput(array("type" => "button", "value" => "Hent", "class" => "form", "style" => "width:70px;", "title" => "Hent automatisk oplysninger baseret på CVR nummer."));
$form->addContent($html->nl());
//virksomhedsnavn
$form->addLabel("company", "Virksomhedsnavn");
$form->addInput(array("type" => "text", "name" => "company", "class" => "form", "style" => "width:300px;"));
$form->addContent($html->nl());
//adresse (faktureringsadresse)
$form->addLabel("addr", "Adresse");
$form->addInput(array("type" => "text", "name" => "addr", "class" => "form", "style" => "width:300px;"));
$form->addContent($html->nl());
//postnr og by
$form->addLabel("zip", "Postnr. By.");
$form->addInput(array("type" => "text", "name" => "zip", "class" => "form", "style" => "width:70px;"));
$form->addInput(array("type" => "text", "name" => "city", "class" => "form", "style" => "width:225px;"));
$form->addContent($html->nl());
//og et lille submit felt til sidst :D
$form->addContent($html->nl());
$form->addInput(array("type" => "submit", "value" => "Opret", "class" => "form"));

$html->add2content($form->getBlock());

?>
