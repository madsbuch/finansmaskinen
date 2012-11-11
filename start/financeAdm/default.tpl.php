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

$template['struct'] = <<<EOF
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>
				Dine apps
			</title>
		<script src="http://static.finansmaskinen.dev/templates/finance/js/init.js">
		</script>
		<link rel="stylesheet" href="http://static.finansmaskinen.dev/templates/finance/css/structure.css" type="text/css" />
		<link rel="stylesheet" href="http://static.finansmaskinen.dev/templates/finance/css/style.css" type="text/css" />
	</head>
	<body>
		<div id="wrap">
			<div id="topnav">
			</div>
			<div id="title">
			</div>
			<div id="menu">
				
			</div>
			<div id="content">
			</div>
			<div id="footer">
			</div>
		</div>
	</body>
</html>
EOF


/**
old, old old:
$template['struct'] = array(
	'tag' => 'html',
	array(
		//information about this tag
		'tag' => 'head',
		'closeTag' => true,
		
		//character incoding
		array('tag' => 'meta',
			'attr' => array(
				'http-equiv' => "Content-Type",
				"content" => "text/html; charset=UTF-8"),
			'closeTag' => false
		),
		
		//the init JavaScript, all js should be managed from here (we don't 
		// want to polute the source (caching and obscuring may allso be easire
		// ;) ))
		array('tag' => 'script','attr' => array(
			"src" => array("static" => "/templates/finance/js/init.js")),
			'closeTag' => true
		),
		
		//and the childs
		array('tag' => 'link','attr' => array(
			'rel' => "stylesheet",
			"href" => array("static" => "/templates/finance/css/structure.css"),
			"type" => "text/css"),
			'closeTag' => false
		),
		array('tag' => 'link', 'attr' => array(
			'rel' => "stylesheet",
			"href" => array("static" => "/templates/finance/css/style.css"),
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
			array('tag' => 'div', 'attr' => array('id' => 'footer'),
				array('tag' => "p", 'content' => "&copy; www.finansmaskinen.dk")
				),
		)
	)
);

*/
//var_dump($template);
?>
