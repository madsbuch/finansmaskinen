<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 11/13/12
 * Time: 8:19 AM
 * setup the systems auth and stuff
 */

define('ROOT', __DIR__.'/../../');
define('PLUGINDIR', ROOT.'/plugins/');
define('DEBUG', false);

function includeRecurse($dirName) {
	if(!is_dir($dirName))
		return false;
	$dirHandle = opendir($dirName);
	while(false !== ($incFile = readdir($dirHandle))) {
		if($incFile != "." && $incFile != "..") {
			if(is_file("$dirName/$incFile")){
				include_once("$dirName/$incFile");
				//var_dump(get_declared_classes (), $incFile);
			}
			elseif(is_dir("$dirName/$incFile"))
				includeRecurse("$dirName/$incFile");
		}
	}
	closedir($dirHandle);
}
require_once(ROOT.'/model/AbstractModel.class.php');
includeRecurse(ROOT.'/model/');
echo "<br />----Just random stuff, do not take into account (from mass inclusion)----<br />" ;
require_once('DataLib.php');
require_once __DIR__ . '/../simpletest/autorun.php';
?>