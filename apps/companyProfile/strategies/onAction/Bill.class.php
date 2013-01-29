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
     * returns the price of the action
     *
     * @return int
     */
    function getPrice()
    {
        //DKK 9.00
        return 900;
    }
}
