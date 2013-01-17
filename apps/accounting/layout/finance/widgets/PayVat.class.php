<?php
/**
 * User: mads
 * Date: 1/16/13
 * Time: 4:31 PM
 *
 * This widget shows Vat that needs to be payed and has a button that resets the vat.
 */

namespace app\accounting\layout\finance\widgets;

use \helper\local as l;

class PayVat extends \helper\layout\LayoutBlock implements \helper\layout\Widget
{

    /**
     * @var \model\finance\accounting\Account
     */
    private $account;

    /**
     * whether this widget is on the frontpage
     * @var bool
     */
    private $frontpage = true;

    /**
     * @param $account
     */
    function __construct(\model\finance\accounting\Account $account){
        $this->account = $account;
    }

    function generate()
    {
        //calculate amount
        $amount = $this->account->income - $this->account->outgoing;

        $this->wrapper->setAttribute('id', 'accounting_widget_container');

        if($amount > 0)
            $color = '#f55';
        else
            $color = '#5f5';


        $ret = '<h2>Moms <small>Betaling af moms</small></h2>
        <div style="font-weight:bold;font-size:450%;color:'.$color.';text-align:center;margin-top:60px;">'.l::writeValuta($amount).'</div>';

        $content = \helper\html::importNode($this->edom, $ret);

        $this->wrapper->appendChild($content);


        if($this->frontpage){
            $btn = $this->edom->createElement('div');
            $btn->setAttribute('style', 'text-align:right;position:absolute;bottom:10px;right:10px;width:50%;');
            $btn->appendChild(
                $this->importNode('<a href="/accounting/vatPayed"
					class="btn btn-primary">Nulstil</a>', $this->edom));

            $this->wrapper->appendChild(
                $this->importNode('
				<div style="position:absolute;bottom:10px;left:10px;">
					<a href="/accounting/" class="btn">Gå til Regnskab</a>
				</div>', $this->edom));

            $this->wrapper->appendChild($btn);
        }


        return $this->wrapper;
    }

    /**
     * som content to wrap output in
     */
    function wrap($wrapper, $dom)
    {
        $this->wrapper = $wrapper;
        $this->edom = $dom;
    }
}
