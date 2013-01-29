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
     * returns the price of the action
     *
     * @return int
     */
    function getPrice();
}
