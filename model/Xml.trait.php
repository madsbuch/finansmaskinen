<?php
/**
* this trait implements functionality, so that a php class behaves like
* an xml class.
*
* conventions:
* normal fieldvariables corrosponds to xml attributes, they have to be
*  atomic
* the special variable $_content corrosponds to the content of the class
*
* this function still takes advantage of the AbstractModel
*/

//this will properbly be implemented later on
/*trait Xml {
	protected  $_content;
	public function __invoke($data){
		$this->_content = $data;
	}
	
	public function __toString(){
		return $this->_content;
	}
}*/

?>
