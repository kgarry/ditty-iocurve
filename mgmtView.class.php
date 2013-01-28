<?php

//require_once("mgmtQualitiesView.class.php"); //fixme use defined path

class MgmtView {
	function __construct() {
//		$this->loadTop();
		$this->loadHeader();
//		$this->loadMenu();
//		$this->loadFooter();
//		$this->loadBottom();
//exit(var_export($_GET, 1));
	}

	public function setType($val) {
		$this->type = $val;
	}

	public function drawMgmtView() {
		// ret
		return 
//			$this->top .
			$this->header .
//			$this->menu .
			$this->loadContent() . 
//			$this->footer .
//			$this->bottom
			'';
	}

	private function loadContent() {
		switch ($this->type) {
			case 'points':
				//fixme use defined path
				$this->loadPointsContent();
				break;
			case 'types':
				//fixme use defined path
                                $this->loadTypesContent();
                                break;
			case 'qualities':
				//fixme use defined path
                                $this->loadQualitiesContent();
                                break;
			
			default:
				$this->content = "An illegal option was passed into object build.";
				break;
		}

		return $this->content . "\n";
	}

	private function loadPointsContent() {
		require_once("mgmtPointsView.class.php"); 	
		$pv = new MgmtPointsView();
		$this->content = $pv->pointsList;
	}

	private function loadTypesContent() {
		require_once("mgmtTypesView.class.php"); 	
		$pv = new MgmtTypesView();
		$this->content = $pv->typesList;
        }

	private function loadQualitiesContent() {
		require_once("mgmtQualitiesView.class.php"); 	
		$pv = new MgmtQualitiesView();
		$this->content = $pv->qualitiesList;
        }

	private function loadTop() {
		$this->top = "<html><head></head><body>";
	}

	private function loadBottom() {
                $this->bottom = "</body></html>";
        }

	private function loadHeader() {
                $this->header = '<div style="border: thin black solid; width: 100%; text-align: center">
			<span onclick="paintWires(\"addPoint.php\", \"mainContent\"">Add Point</span> | 
			<span onclick="paintWires(\"addType.php\", \"mainContent\"">Add Type</span> | 
			<span onclick="paintWires(\"addQuality.php\", \"mainContent\"">Add Quality</span></div>'; 
	}

	private function loadMenu() {
                $this->menu = '<div style="border: thin black solid; width: 100%">
			<a href="?ct=points">Points</a> | 
			<a href="?ct=types">Types</a> | 
			<a href="?ct=qualities">Qualities</a></div>';
        }

	private function loadFooter() {
                $this->footer = '<div style="border: thin black solid; width: 100%">footer</div>';
        }

	public function makeEditLink($type, $Id) {
		return '<span class="strong">[ <a href="google.com/Edit/'.$type.'/'.$Id.'" target="_blank">edit</a> ]</span> ';
	}

	public function makeCloneLink($type, $Id) {
		return '<span class="strong">[ <a href="google.com/Clone/'.$type.'/'.$Id.'" target="_blank">clone</a> ]</span> ';
	}
}
