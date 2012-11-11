<?php
/**
* Fieldstypes
* This file defines the different types, nab entries can be defined as
*/

$fieldtype = array(
	//array("Title", "regExp validation string" | false);
	0  => array("name", false),			//name, nickname
	1  => array("company", false),		//this should not contain any prekeys
	2  => array("tlf", false),			//call enabled device
	2  => array("mobile", false),		//sms and call enabled device
	3  => array("address", false),
	4  => array("webpage", false),
	5  => array("email", false),
	6  => array("date", false),			//validated as datetime
	7  => array("email", false),	
	8  => array("relation", false),
	9  => array("chat", false),			//nickname in chatprotocols
	10 => array("tlfnr", false),
	11 => array("reference", false),	//reference to another nab row ()
	12 => array("custom", false),		//custom field. strlen(key and value) < 100
	
);

/**
* Prekeys are keys  wich are predetermed. These are english
* language specific should be implemented. Maybe in db?
*
* PREKEYS ARE NOT IMPLEMENTED HERE; BUT IN THE APP!
*/
?>
