<?php

class Page {
	function __construct() {
		$this->assets = array('scripts', 'styles');
		$this->addScripts('http://code.jquery.com/jquery-latest.min.js');
		/*if (__PAGE_DEBUG_MODE__) {
			$this->addScripts('media/js/toggleExplain.js');
		}*/
		
		//$this->addStyles();
	}

/**
* $param	$force causes the debug to always display
**/
	public function explain($matter=false, $extra_info='', $force=false) {
		if (!$matter) { return; }

                if (__PAGE_DEBUG_MODE__ !== true && !$force) { return; }

                $trace = debug_backtrace();
                $caller = '';
                if (!empty($trace[1]['function'])) {
                        $caller = ((string) $trace[1]['function']);
                }

                $info = ' * EXPLAINING * ' . __CLASS__ . '::' . $caller .'-' . $extra_info . "\n";
                echo '<textarea class="explain">' . $info;
                if (!$matter) {
                        echo var_export($this);
                } else {
                        echo var_export($matter);
                }
                echo '</textarea>';
        }

	function renderHeader() {
		$this->renderScripts();
		$this->renderStyles();
		
		$val = array("<html>",
			"<head>", 
			$this->scripts,
			$this->styles, 
			"</head>",
			"<body><h1>Game Test</h1>");
		$this->header = implode("\n", $val);
	} 

	function renderFooter() {
		$val = '</body>' . 
			'</html>';
		$this->footer = $val;
	} 

/**
* $todo		add sanity/competeness checks
**/
	function addScripts($srcs=null) {
		if (!is_array($srcs)) {
			$srcs = array($srcs);
		}

		foreach ($srcs as $src) {
			$this->assets['scripts'][] = $src;
		}
	}

/**
*
**/
//	function removeScripts() { }

/*
* $todo		make updatable explode/implode
**/
	function renderScripts() {
		foreach ($this->assets['scripts'] as $src) {
			$val[] = '<script type="text/javascript" src="' . $src . '"></script>';
		}

		$this->scripts = implode("\n", $val);
	}

/**
* $todo		add sanity/competeness checks
**/
	function addStyles($srcs=null) {
		if (!is_array($srcs)) {
			$srcs = array($srcs);
		}

		foreach ($srcs as $src) {
			$this->assets['styles'][] = $src;
		}
	}

/**
*
**/
//	function removeStyles() { }

/*
* $todo		make updatable explode/implode
**/
	function renderStyles() {
		foreach ($this->assets['styles'] as $src) {
			$val[] = '<link rel="stylesheet" type="text/css" href="' . $src . '">';
		}

		$this->styles = implode("\n", $val);
	}
}


/**
*
**/
class BasicPage extends Page{
	function __construct() {
		parent::__construct();

		$this->addStyles(array('media/css/tile.css', 'media/css/arena.css'));
		$this->addScripts('media/js/reload.js');

		$this->reloadDelay = null;
		$this->renderHeader();
		$this->renderFooter();
	}
}
