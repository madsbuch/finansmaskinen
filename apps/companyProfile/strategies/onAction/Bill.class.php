<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 1/29/13
 * Time: 1:38 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\companyProfile\strategies\onAction;
class Bill implements OnAction
{

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
        return true;
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
        // TODO: Implement getTicketPrice() method.
    }

    /**
     * returns the price of the action
     *
     * @return int
     */
    function getPrice()
    {
        //DKK 9.00
        return 900;
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
        return 'Free for now.';
    }
}
