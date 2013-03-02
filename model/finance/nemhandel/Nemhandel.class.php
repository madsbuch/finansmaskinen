<?php
/**
 * User: mads
 * Date: 2/26/13
 * Time: 1:35 PM
 *
 * used for communicating with the nemhandel service
 */
class Nemhandel extends \model\AbstractModel
{
	/**
	 * XML representation of document
	 * @var string
	 */
	protected $document;

	/**
	 * Some message, f.eks. used for transport layer exception
	 * @var string
	 */
	protected $message;
}
