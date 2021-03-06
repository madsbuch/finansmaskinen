<?php
/**
 * User: Mads Buch
 * Date: 12/20/12
 * Time: 8:41 PM
 */
namespace helper\accounting;
interface Queries
{

	//region accounts

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
	 * return a query that updates an account.
	 * parameters is the same as the insert function
	 * @return string
	 */
	function updateAccount();

	/**
	 * returns prepared statement that deletes an account
	 * parameters:
	 *  code
	 *  grp_id
	 *
	 * @return string
	 */
	function deleteAccount();

    /**
     * creates a query that returns a table of following col's
     *  amount_in
     *  amount_out
     *  type
     *  account
     *
     * it requires following parameters:
     *  accounting
     *  grp
     *
     * @return string
     */
    function getSumsOnVatAccounts();

	/**
	 * returns a string that updates a vatcode object to the DB
	 * following fields may be set
	 *  code - name of the code that needs to be set
	 *  type
	 *  name
	 *  description
	 *  account
	 *  contraAccount
	 *  percentage
	 *  deductionPercentage
	 *  contraDeductionPercentage
	 *  principle
	 *  taxCategoryID
	 *  grp - the group
	 *
	 * @return string
	 */
	function updateVatCode();

	/**
	 * the same sa the update function, only difference is that code is for insertion and not queryring
	 *
	 * @return string
	 */
	function createVatCode();

	/**
	 * takes
	 *  account_id
	 *
	 * @param $tags array of tags
	 * @return string
	 */
	function setTags($tags);

	/**
	 * takes
	 *  code - code of account
	 *  grp_id
	 *
	 * @param $tags
	 * @return string
	 */
	function setTagsByAccountCode($tags);

	/**
	 * takes
	 *  account_id object id of account
	 *
	 * @return string
	 */
	function getTagsForAccount();

	/**
	 * returns tags
	 *
	 * takes:
	 *  grp_id
	 *
	 * @return string
	 */
	function getAllTags();

	//endregion

	//region transactions
    /**** TRANSACTION ****/

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
	 * returns a query to get all transactions, takes folloing parameters:
	 *  start   starting row
	 *  num     row number limit
	 *  accounting
	 *
	 *
	 * @internal param $id
	 * @return string
	 */
    function getTransactions();

	/**
	 * takes:
	 *  id
	 *  account_id
	 *
	 * @return string
	 */
	function getSingleTransaction();

	/**
	 * returns a query, that extracts a single reference based on
	 *
	 * takes
	 *  referenceText   that are unique
	 *  accounting_id   id of accounting
	 *
	 * @return string
	 */
	function getTransactionByReference();

	/**
	 * returns a query that takes following:
	 *  start
	 *  num
	 *  accountCode
	 *  grp
	 *
	 * @param $accounting null if all postings for accounting is to be returned
	 * @return string
	 */
	function getPostings($accounting);

	/**
	 * returns a query that takes followng:
	 *  transactionID   id of transactions postings are for.
	 *
	 * @return string
	 */
	function getPostingsForTransaction();

	//endregion

}
