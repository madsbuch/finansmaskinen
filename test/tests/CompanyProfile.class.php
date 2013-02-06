<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 1/30/13
 * Time: 12:38 PM
 * To change this template use File | Settings | File Templates.
 */

//include the auxilery
require_once 'DataLib.php';
require_once __DIR__ . '/../../helpers/rpc/controller.class.php';
require_once __DIR__ . '/../../helpers/rpc/Finance.class.php';

class CompanyProfile extends UnitTestCase
{


    /**
     * this is specially for testing, that free tickets cannot be altered
     */
    function testUnableToChangeSecuredValues(){

    }
}