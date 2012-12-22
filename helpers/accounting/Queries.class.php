<?php
/**
 * User: Mads Buch
 * Date: 12/20/12
 * Time: 8:41 PM
 */
namespace helper\accounting;
interface Queries
{

	/**
	 * returns table as follows:
	 *  id:         globally unique account id
	 *  name:       string, name of account
	 *  code:       account code
	 *  vat:        vatcode of account
	 *  type:       type of account, 1, 2, 3, 4 (income, expense...)
	 *  flags:      int that represents flags
	 *  amount_in   amount gone in to this account
	 *  amount_out  amount gone out of this account
	 *
	 * @param $grp
	 * @return string
	 */
	function getAllAccounts($grp);

	/**
	 * returns prepared statement for inserting transaction
	 *
	 * followinf named parameters exist:
	 *  date
	 *  referenceText   that are unique
	 *  approved        whether it's approved
	 *  accounting_id   id of accounting
	 *
	 * @return string
	 */
	function insertTransaction();

	/**
	 * posting insertion prepare
	 *
	 * prepares ar query with named parameters as follows:
	 *  amount_in       amount going into the account
	 *  amount_out      amount going out
	 *  transaction_id  id of transaction
	 *  account         account code
	 *  grp             grp id
	 */
	function insertPosting();

	/**
	 * returns prepared statement that creates an account
	 *
	 * following named parameters:
	 *  grp_id      the group id
	 *  code        account code
	 *  dfa         default reflection account
	 *  name        name
	 *  type        type
	 *  vat         vat
	 *  flags
	 *
	 * @return string
	 */
	function insertAccount();

	/**
	 * returns prepared statement that deletes an account
	 * parameters:
	 *  code
	 *  grp_id
	 *
	 * @return string
	 */
	function deleteAccount();
}
