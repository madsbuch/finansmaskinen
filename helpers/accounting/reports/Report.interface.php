<?php
/**
 * User: Mads Buch
 * Date: 1/7/13
 * Time: 1:17 AM
 */

namespace helper\accounting\reports;

interface Report
{
	/**
	 * generates some report object
	 *
	 * @return mixed
	 */
	function generateReport();

}
