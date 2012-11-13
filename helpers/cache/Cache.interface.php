<?php
/**
 * phpCacher
 *
 * A framwork for building extensible caching engine in PHP
 *
 * @author    Mads Buch <madspbuch at gmail dot com>
 * @license   MIT License
 */

namespace helper\cache;

interface Cache{
	function offsetExists($offset);
	function offsetUnset($offset);
	function dataGet($offset);
	function dataSet($offset, $value, $expiry);
	function gc();//garbage collection;
}
?>
