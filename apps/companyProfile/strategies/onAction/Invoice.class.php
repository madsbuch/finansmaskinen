<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 1/29/13
 * Time: 1:36 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\companyProfile\strategies\onAction;
class Invoice implements OnAction

{
    /**
     * @param $company the compay object
     */
    function __construct($company){

    }

    /**
     * returns true, if this action should not be billed
     *
     * this is if the action is covered by some monthly paud subscription
     * and therefore is none paid
     *
     * @return bool
     */
    function coveredBySubscription()
    {
        return false;
    }

    /**
     * returns the price in tickets
     *
     * if this is used, getPrice is not called
     *
     * @return int
     */
    function getTicketPrice()
    {
        return 0;
    }

    /**
     * returns the price of the action
     *
     * @return int
     */
    function getPrice()
    {
        //DKK 19.00
        return 1900;
    }

    /**
     * returns a message for given action
     *
     * e.g. if the action costs 1 free ticket, a warning message is to be returned
     * if the action costs $10 a message saying that $10 is wthdrawn should be returned
     *
     * @return string
     */
    function getMessage()
    {
        return 'A ticket is taken.';
    }
}
