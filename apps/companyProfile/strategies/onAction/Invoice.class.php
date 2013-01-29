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
     * returns the price of the action
     *
     * @return int
     */
    function getPrice()
    {
        //DKK 19.00
        return 1900;
    }
}
