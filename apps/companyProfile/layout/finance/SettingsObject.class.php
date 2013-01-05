<?php
/**
 * User: mads
 * Date: 12/25/12
 * Time: 8:44 PM
 */

namespace app\companyProfile\layout\finance;
class SettingsObject extends \helper\layout\LayoutBlock
{

    private $obj;
    private $modalID;

    function __construct($settingsObj, $modalID, $for){
        $this->obj = $settingsObj;
        $this->modalID = $modalID;
        $this->for = $for;
    }

    function generate(){
        $form = "";

        //the object representing current settings is an associative array, as we don't know the type
        foreach($this->obj->fields as $key => $desc){
            $form .= '<label>'
                . __($desc)
                . '</label><input type="text" name="'
                .$key
                .'" value="'
                .$this->obj->settings[$key]
                .'" />';
        }

        $ret = '
<div class="modal hide fade" id="' . $this->modalID . '">
	<form method="post" action="/companyProfile/updateSettings/'.$this->for.'">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>'.__($this->obj->title).'</h3>
		</div>
		<div class="modal-body">
			'.$form.'
		</div>
		<div class="modal-footer">
			<a href="#" class="btn" data-dismiss="modal">Close</a>
			<input type="submit" value="Gem" class="btn btn-primary" />
		</div>
	</form>
</div>
		';
        //$element = new \helper\html\HTMLMerger($ret, $this->company);
        return $ret;// $element->generate();
    }
}
