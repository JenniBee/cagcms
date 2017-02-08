<?php
class htmlform {
	public $formaction;
	public $formmethod;
	public $formname;
	public $formfields;

	function __construct($array_vars = array()) {
		foreach($array_vars as $key=>$val) {
			$this->$key = $val;
		}
	}
	
	function buildform() {
		if(empty($this->formfields)) {
			return false;
		}
		else {
			$myreturn = '<form name="'.$this->formname.'" id="'.$this->formname.'" method="'.$this->formmethod.'" action="'.$this->formaction.'">';
			foreach($this->formfields as $myfield) {
				$myreturn .= $myfield->buildfield();
			}
			$myreturn .= '</form>';
			return $myreturn;
		}
	}
}
?>