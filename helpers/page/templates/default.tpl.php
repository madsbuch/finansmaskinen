<?php
$template['settings'] = array(
	'static' => false, //if true, none js is applied
	
	//js settings (is static = false)
	'ajax' => true,
	'modal' => true, //use modals instead of popups
	'libs' => array("jquery >= 1.5"),
	
	//ouput settings
	'minimize' => false,
	'scheme' => ''//eks: XML HTML etc
	
);

/**
* template struct
*
* this is the main struct. What is made here is the sceleton. The html functions
* adds to this struct, and finaly, it can be rendered into various versions of html
* or another markup
*
* default there is following keywords:
*
* 1. attr: attributes to the element
* 2. content: content to the element
* 3. tag: the tag name
* 4. closeTag: should the tag be closed? default: true (if false, and output=xhtml,
*    tag will be closed inline
* 5. the rest of the array is treatet as childes (indexes)
*
* if an attribute contains an array, it will be parsed as a link
*/
$template['struct'] = array(
	'tag' => 'html',
	array(
		//information about this tag
		'tag' => 'head',
		'closeTag' => true,
		
		//character incoding
		array('tag' => 'meta',
			'attr' => array(
				'http-equiv' => "stylesheet",
				"content" => "text/html; charset=UTF-8"),
			'closeTag' => false
		),
		
		//and the childs
		array('tag' => 'link','attr' => array(
			'rel' => "stylesheet",
			"href" => array("static" => "/templates/default/css/structure.css"),
			"type" => "text/css"),
			'closeTag' => false
		),
		array('tag' => 'link', 'attr' => array(
			'rel' => "stylesheet",
			"href" => array("static" => "/templates/default/css/style.css"),
			"type" => "text/css"),
			'closeTag' => false
		),
		array('tag' => 'link', 'attr' => array(
			'rel' => "stylesheet",
			"href" => array("static" => "/templates/default/css/list.css"),
			"type" => "text/css"),
			'closeTag' => false
		),
	),
	array(
		'tag' => 'body',
		array(
			'tag' => 'div',
			'attr' => array('id' => 'wrap'),
			array('tag' => 'div', 'attr' => array('id' => 'topnav')),
			array('tag' => 'div', 'attr' => array('id' => 'title')),
			array('tag' => 'div', 'attr' => array('id' => 'menu')),
			array('tag' => 'div', 'attr' => array('id' => 'content')),
			array('tag' => 'div', 'attr' => array('id' => 'footer')),
		)
	)
);


//var_dump($template);
?>
