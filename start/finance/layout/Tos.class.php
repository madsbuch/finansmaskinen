<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 1/29/13
 * Time: 12:46 PM
 * To change this template use File | Settings | File Templates.
 */

namespace start\finance\layout;

class Tos extends \helper\layout\LayoutBlock
{

    function generate()
    {
        return '
        <p>
            Senere kommer der et par enkelte vilkår :-)
        </p>
        <a href="/main/tos/agree" class="btn btn-primary">Enig!</a>
        ';
    }
}
