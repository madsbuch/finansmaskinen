<?php
/**
 * User: mads
 * Date: 1/16/13
 * Time: 4:31 PM
 *
 * This widget shows Vat that needs to be payed and has a button that resets the vat.
 */
class PayVat extends \helper\layout\LayoutBlock implements \helper\layout\Widget
{

    function generate()
    {
        $this->setJS();

        $this->wrapper->setAttribute('id', 'accounting_widget_container');

        $ret = '
		<h2>Regnskab <small>Beholdninger</small></h2>
		<div id="accounting-stat" style="height:150px;width:100%;">
		</div>';

        $content = \helper\html::importNode($this->edom, $ret);

        $this->wrapper->appendChild($content);


        if($this->frontpage){
            $btn = $this->edom->createElement('div');
            $btn->setAttribute('style', 'text-align:right;position:absolute;bottom:10px;right:10px;width:50%;');
            $btn->appendChild(
                $this->importNode('<a href="/accounting/vat"
					class="btn btn-primary">Angiv moms</a>', $this->edom));

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
