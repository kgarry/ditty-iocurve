<?php
require_once("point.class.php");
require_once("type.class.php");
require_once("quality.class.php");

/***
*
***/
class TypeCrud extends Type {
	public function renderCreate() {
		$out = '<html>
			<head>
			  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
			</head>
			<body bgcolor="#e5e5e5">';
	
/*		$out .= '<script>
$(document).ready(function() {
console.log("ht: "+$(window).height());
//  $(window).height(); // New height
console.log("wd: "+$(window).width());
//  $(window).width(); // New width
});
</script>';*/

		$out .= '<form id="theForm" method="POST" action="type/create">'
			. '<div class="form_elem">Name<hr>'
			. '<input name="name" type="text">'
			. '</div><div class="form_elem">Machine Name<hr>'
			. '<input name="machine" type="text">'
			. '</div><div class="form_elem">Description<hr>'
			. '<textarea name="lgDesc"></textarea>'
			. '</div><div class="form_elem">Is this a core item?<hr>'
			. '<input name="isCore" type="radio" value=0> No'  
			. '<input name="isCore" type="radio" value=1> Yes'  
			. '</div><div class="form_elem">Mode? <uhhh?><hr>'
			. '<input name="isCore" type="radio" value=0> 0'  
			. '<input name="isCore" type="radio" value=1> 1'  
			. '</div><div class="">'
			. '<input type="submit" value="Commit">'
			. '</div></form>';

		$out .= $this->renderTypeLQual();
		
		$out .= '</body>
			</html>';

                $this->formCreate = $out;
	}

	private function renderTypeLQual() {

	}

	private function create() {
		
	}
}

/***
*
***/
class Crud {
	public function renderForm() {
		$out = '<form method="GET" action="' . $this->operation . '-' . $this->objectType . '">'
			. '<input type="submit" value="Commit">'
			. '</form>';

		return $out;
	}	
}

/***
*
***/
class PointCrud extends Crud {
	function __construct() {
		$this->objectType = "Point";
	}

/***
* @param	$op 
***/
	public function setOperation($op) {
		$allowed = array('create', 'update', 'deactivate');
		$this->operation = $op;
	}
}
