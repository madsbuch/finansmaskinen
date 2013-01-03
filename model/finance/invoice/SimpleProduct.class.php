<?php
/**
 * Created by JetBrains PhpStorm.
 * User: mads
 * Date: 12/22/12
 * Time: 4:41 PM
 * To change this template use File | Settings | File Templates.
 */

namespace model\finance\invoice;
class SimpleProduct
{
    /**
     * product id (not object, but product)
     * @var string
     */
    protected $productID;

    /**
     * quantity of product
     * @var int
     */
    protected $quantity;
}
