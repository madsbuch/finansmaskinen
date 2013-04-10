/**
 * Created with JetBrains PhpStorm.
 * User: mads
 * Date: 4/10/13
 * Time: 12:14 PM
 * To change this template use File | Settings | File Templates.
 */

interface JQueryStatic {
	//for the Flot charts, that implement following methods:
	plot(placeholder, data, options): Plot;
}

interface JQuery {
	//for the Flot charts, that implement following methods:
	plot(placeholder, data, options): Plot;
}

interface Plot{
	getData();
	pointOffset(object);
	getPlaceholder();
}