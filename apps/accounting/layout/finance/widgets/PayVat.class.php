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
            $color = '#f33';
        else
            $color = '#3f3';


        $ret = '<h2>Moms <small>Betaling af moms</small></h2>
        <div style="font-weight:bold;font-size:450%;color:'.$color.';text-align:center;margin-top:60px;">'.l::writeValuta($amount).'</div>
        ';

        $content = \helper\html::importNode($this->edom, $ret);

        $this->wrapper->appendChild($content);


        if($this->frontpage){
            $btn = $this->edom->createElement('div');
            $btn->setAttribute('style', 'text-align:right;position:absolute;bottom:10px;right:10px;width:50%;');
            //button for applying payment
            $btn->appendChild(
                $this->importNode('<a href="#applyPaymentPayVat" data-toggle="modal"
					class="btn btn-primary">Nulstil</a>', $this->edom));


            //going to accounting
            $this->wrapper->appendChild(
                $this->importNode('
				<div style="position:absolute;bottom:10px;left:10px;">
					<a href="/accounting" class="btn">Gå til Regnskab</a>
				</div>', $this->edom));

            $this->wrapper->appendChild($btn);

            //the modal
            $this->wrapper->appendChild($this->importNode('<div class="modal hide fade" id="applyPaymentPayVat">
            <form method="post" action="/accounting/vatPayed" id="addNewProductForm">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3>'.__('Apply payment').'</h3>
                </div>
                <div class="modal-body">

                    <label>Hvilken konto er pengene kommet ind på:</label>
                    <div class="input-append">
                        <input type="text" class="picker"
                            id="accounting_pay" style="width:80%"
                            data-listLink="/accounting/autocompleteAccounts/payable/do/"
                            data-objLink="/accounting/getAccount/" /><a href="#accounting_pay"
                            class="btn pickerDP"><i class="icon-circle-arrow-down"></i></a>
                    </div>
                    <input type="hidden" id="accounting_paycode" name="AccountCode" />
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn" data-dismiss="modal">Anuller</a>
                    <input type="submit" class="btn btn-primary" value="Marker som betalt" />
                </div>
            </form>
        </div>', $this->edom));

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
