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
	function getAllAccounts($grp, $flags = 0, $accounts=array()){
		$add = ' AND flags & ' . $flags . ' = ' . $flags . '';

		if(is_array($accounts)){
			$first = 'AND ';
			foreach($accounts as $acc){
				$add .= ' '.$first.' acc.code = '. (int) $acc;
				$first = 'OR';
			}
			$add .= '';
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
			    SUM(posts.amount_out) as amount_out
			FROM
			    accounting_accounts as acc
			    LEFT OUTER JOIN accounting_postings as posts ON posts.account_id = acc.id
			WHERE
			    acc.grp_id = '.$grp.' #restricting on group
			    '.$add.'
			GROUP BY acc.code';
	}

	function insertTransaction(){
		return '
			insert into
			    accounting_transactions (date, reference, approved, accounting_id)
			values
			    (:date, :referenceText, :approved, :accounting_id);';
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

	/**** Accounts ****/

	function insertAccount(){
		return 'INSERT INTO accounting_accounts
			(`grp_id`, `code`, `default_reflection_account`, `name`, `type`, `vat`, `flags`)
			VALUES
			(:grp_id, :code, :dfa, :name, :type, :vat, :flags);';
	}

	function deleteAccount(){
		return 'DELETE FROM accounting_accounts WHERE code = :code AND grp_id = :grp_id LIMIT 1';
	}

    /**** Vat ****/
    function getSumsOnVatAccounts(){
        return 'select
            SUM(a_in) as amount_in,
            SUM(a_out) as amount_out,
            type
        from
        (select DISTINCT
            posts.id as post_id,
            vat.type as type,
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

}
