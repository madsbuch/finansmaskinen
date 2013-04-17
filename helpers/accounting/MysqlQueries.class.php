<?php
/**
 * User: Mads Buch
 * Date: 12/19/12
 * Time: 4:39 PM
 */
namespace helper\accounting;

/**
 * queries for mysql dbms
 */
class MysqlQueries implements \helper\accounting\Queries
{
	/**** Accounts ****/
	//region accounts
	function getAllAccounts($grp, $flags = 0, $accounts=array(), $type = null, $tags = array()){
		$add = ' AND flags & ' . $flags . ' = ' . $flags . '';

		if(is_array($accounts)){
			$first = 'AND ';
			foreach($accounts as $acc){
				$add .= ' '.$first.' acc.code = '. (int) $acc;
				$first = 'OR';
			}
			$add .= '';
		}

		if(!is_null($type)){
			$add .= ' AND acc.type = '.(int) $type;
		}

		return '
			SELECT
			    acc.id,
			    acc.name,
			    acc.code,
			    acc.vat,
			    acc.type,
			    acc.flags,
			    acc.grp_id,
			    SUM(posts.amount_in) as amount_in,
			    SUM(posts.amount_out) as amount_out,
			    acc.currency
			FROM
			    accounting_accounts as acc
			    LEFT OUTER JOIN accounting_postings as posts ON posts.account_id = acc.id
			WHERE
			    acc.grp_id = '.$grp.' #restricting on group
			    '.$add.'
			GROUP BY acc.code';
	}

	function insertAccount(){
		return 'INSERT INTO accounting_accounts
			(`grp_id`, `code`, `default_reflection_account`, `name`, `type`, `vat`, `flags`, `currency`)
			VALUES
			(:grp_id, :code, :dfa, :name, :type, :vat, :flags, :currency);';
	}
	function updateAccount(){
		return '
		UPDATE accounting_accounts
		SET
			`default_reflection_account` = :dfa,
			`name` = :name,
			`type` = :type,
			`vat` =  :vat,
			`flags` = :flags,
			`currency` = :currency
		WHERE
				grp_id = :grp_id
			AND code = :code;';
	}

	function deleteAccount(){
		return 'DELETE FROM accounting_accounts WHERE code = :code AND grp_id = :grp_id LIMIT 1';
	}

	function setTags($tags){
		$insert = '(:account_id,' . implode('\'), (:account_id, \'', $tags) . ')';
		return "
			DELETE FROM accounting_account_tags WHERE id = :account_id;
			INSERT INTO
				accounting_account_tags (account_id, tag)
			VALUES $insert;

		";
	}

	function setTagsByAccountCode($tags){
		$insert = '(@id,\'' . implode('\'), (@id, \'', $tags) . '\')';
		return "
			SET @id = (SELECT id FROM accounting_accounts WHERE grp_id = :grp_id AND code = :code);
			DELETE FROM accounting_account_tags WHERE account_id = @id;
			INSERT INTO
				accounting_account_tags (account_id, tag)
			VALUES $insert;

		";
	}


	function getTagsForAccount(){
		return 'SELECT tag FROM accounting_account_tags WHERE account_id = :account_id;';
	}

	function getAccountsForTags($tags){


	}

	function getAllTags(){
		return '
			SELECT
				DISTINCT tags.tag
			FROM
				accounting_account_tags as tags,
				accounting_accounts as account
			WHERE
					tags.account_id = account.id
				AND account.grp_id = :grp_id;';
	}

	//endregion

	//region transactions
	/**** transactions ****/

	function getTransactions(){
		return 'SELECT
          id,
          date,
	      reference,
	      approved
	    FROM accounting_transactions WHERE accounting_id = :accounting
	      ORDER BY date DESC LIMIT :start, :num';
	}


	function getSingleTransaction(){
		return 'SELECT
          id,
          date,
	      reference,
	      approved
	    FROM accounting_transactions WHERE id = :id AND accounting_id = :accounting';
	}

	function insertTransaction(){
		return '
			insert into
			    accounting_transactions (date, reference, approved, accounting_id)
			values
			    (:date, :referenceText, :approved, :accounting_id);';
	}

	function getTransactionByReference(){
		return 'SELECT
          id,
          date,
	      reference,
	      approved
	    FROM accounting_transactions WHERE reference = :referenceText AND accounting_id = :accounting_id';
	}

	//endregion

	//region VAT

    /**** Vat ****/
    function getSumsOnVatAccounts(){
        return 'select
            SUM(a_in) as amount_in,
            SUM(a_out) as amount_out,
            type,
            account
        from
        (select DISTINCT
            posts.id as post_id,
            vat.type as type,
            vat.account as account,
            posts.amount_in as a_in,
            posts.amount_out as a_out
        from
            accounting_vat_codes as vat,
            accounting_accounts as acc,
            accounting_transactions as dbt,
            accounting_postings as posts
        WHERE
                vat.grp_id = :grp							#get only those for this group
                    AND	vat.account = acc.code				#select neeeded accounts
                    AND	dbt.accounting_id = :accounting	    #restricting to accounting
                        AND posts.transaction_id = dbt.id	#restricting postings to be in transactions
                            AND posts.account_id = acc.id	#restricting postings t correct accounts
        ) as getPostings
        GROUP BY
            type';

    }

	function updateVatCode(){
		return '
		UPDATE
			accounting_vat_codes
		SET
			`name` = :name,
			`type` = :type,
			`percentage` = :percentage,
			`account` = :account,
			`ubl_taxCatagory` = :taxCategoryID,
			`description` = :description,
			`contra_account` = :contraAccount,
			`deduction_percentage` = :deductionPercentage,
			`contra_deduction_percentage` = :contraDeductionPercentage,
			`principle` = :principle
		WHERE
				grp_id = :grp
			AND vat_code = :code
		';
	}

	function createVatCode(){
		return '
		INSERT INTO
			accounting_vat_codes
			(`name`, `type`, `percentage`, `account`, `ubl_taxCatagory`, `description`, `contra_account`, `deduction_percentage`,
				`contra_deduction_percentage`, `principle`, `grp_id`, `vat_code`)
		VALUES
			(:name, :type, :percentage, :account, :taxCategoryID, :description, :contraAccount, :deductionPercentage,
				:contraDeductionPercentage, :principle, :grp, :code);';
	}

	//endregion

	//region postings
	/**** Postings ****/
	function getPostings($accounting){
		$limit = is_null($accounting) ? '' : 'AND transaction_id IN (select id from accounting_transactions WHERE accounting_id = \''.$accounting.'\')';

		return '
			select
			    *
			from
			    accounting_postings
			where
			    account_id = (select id from accounting_accounts where grp_id = :grp and code = :accountCode)
			    '.$limit.'
			limit :start,:num';
	}

	function insertPosting(){
		return '
		INSERT INTO accounting_postings
		    (`account_id`, `amount_in`, `amount_out`, `transaction_id`)
		VALUES(
		    (select
		        id
		    from
		        accounting_accounts as a
		    WHERE
		            a.code = :account
		        AND a.grp_id = :grp),
		   :amount_in,
		   :amount_out,
		   :transaction_id );';
	}

	function getPostingsForTransaction(){
		return '
			SELECT
				ap.*, aa.code as account_code
			FROM
				accounting_postings as ap,
				accounting_accounts as aa
			WHERE
					transaction_id = :transactionID
				AND	ap.account_id = aa.id;
		';
	}

	//endregion

}
