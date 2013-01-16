<?php

//require_once("IocMgmtQualitiesView.class.php"); //fixme use defined path

class IocMgmtView {
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

	public function drawIocMgmtView() {
		// ret
		return 
//			$this->top .
			$this->header .
			$this->menu .
			$this->loadContent() . 
//			$this->footer .
//			$this->bottom
			'';
	}

	private function loadContent() {
		switch ($this->type) {
			case 'points':
				//fixme use defined path
				require_once("IocMgmtPointsView.class.php"); 	
				$this->loadPointsContent();
				break;
			case 'types':
				//fixme use defined path
				require_once("IocMgmtTypesView.class.php"); 	
                                $this->loadTypesContent();
                                break;
			case 'qualities':
				//fixme use defined path
				require_once("IocMgmtQualitiesView.class.php"); 	
                                $this->loadQualitiesContent();
                                break;
			
			default:
				$this->content = "An illegal option was passed into object build.";
				break;
		}

		return $this->content . "\n";
	}

	private function loadPointsContent() {
		$pv = new IocMgmtPointsView();
		$this->content = $pv->pointList;
	}

	private function loadTypesContent() {
		$pv = new IocMgmtTypesView();
		$this->content = $pv->typeList;
        }

	private function loadQualitiesContent() {
		$pv = new IocMgmtQualitiesView();
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

}
