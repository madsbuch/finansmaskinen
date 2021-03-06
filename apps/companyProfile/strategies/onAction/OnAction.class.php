<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 1/29/13
 * Time: 1:32 PM
 * To change this template use File | Settings | File Templates.
 */
namespace app\companyProfile\strategies\onAction;
interface OnAction
{
    /**
     * returns true, if this action should not be billed
     *
     * this is if the action is covered by some monthly paud subscription
     * and therefore is none paid
     *
     * @return bool
     */
    function coveredBySubscription();

    /**
     * returns the price in tickets
     *
     * this is only called if coveredBySubscription returns false
     *
     * if this is used, getPrice is not called
     *
     * @return int
     */
    function getTicketPrice();

    /**
     * returns the price of the action
     *
     * this is only called, if tickets werent sufficient
     *
     * @return int
     */
    function getPrice();

    /**
     * returns a message for given action
     *
     * e.g. if the action costs 1 free ticket, a warning message is to be returned
     * if the action costs $10 a message saying that $10 is wthdrawn should be returned
     *
     * This is NON translated.
     *
     * @return string
     */
    function getMessage();
}
