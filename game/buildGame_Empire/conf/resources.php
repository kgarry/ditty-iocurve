<?php

$types = array('resource', 'land');

$items = array (
	'Iron Ore' => array(
		'types' => $types,
		'quals' => array(
			'scarcity' => 'normal',
			'seasonal' => 1111,
			'renewable' => false,
			'Stash Size' => 'huge',
			),
		),
	'Game' => array(
		'types' => $types,
		'quals' => array(
			'scarcity' => 'low',
			'seasonal' => 1110, // no winter
			'renewable' => true,
			'Stash Size' => 'normal',
			),
		),
	'Fish' => array(
		'types' => $types,
		'quals' => array(
			'scarcity' => 'low',
			'seasonal' => 1111,
			'renewable' => true,
			'Stash Size' => 'large',
			),
		),
// WOOD
	'Pine' => array(
		'types' => array('resource', 'land', 'wood'),
		'quals' => array(
			'scarcity' => 'low',
			'seasonal' => 1111,
			'renewable' => true,
			'Stash Size' => 'huge',
			),
		),
	'Oak' => array(
		'types' => array('resource', 'land', 'wood'),
		'quals' => array(
			'scarcity' => 'normal',
			'seasonal' => 1111,
			'renewable' => true,
			'Stash Size' => 'huge',
			),
		),
	'Teak' => array(
		'types' => array('resource', 'land', 'wood'),
		'quals' => array(
			'scarcity' => 'high',
			'seasonal' => 1111,
			'renewable' => true,
			'Stash Size' => 'normal',
			),
		),
	'Ironwood' => array(
		'types' => array('resource', 'land', 'wood'),
		'quals' => array(
			'scarcity' => 'high',
			'seasonal' => 1110, // no winter
			'renewable' => true,
			'Stash Size' => 'small', 
			),
		),
// Stone
	'Slate' => array(
		'types' => array('resource', 'land', 'stone'),
		'quals' => array(
			'scarcity' => 'high',
			'seasonal' => 1111,
			'renewable' => true,
			'Stash Size' => 'small',
			),
		),
	'Sandstone' => array(
		'types' => array('resource', 'land', 'stone'),
		'quals' => array(
			'scarcity' => 'high',
			'seasonal' => 1111,
			'renewable' => true,
			'Stash Size' => 'small',
			),
		),
	'Marble' => array(
		'types' => array('resource', 'land', 'stone'),
		'quals' => array(
			'scarcity' => 'high',
			'seasonal' => 1111,
			'renewable' => false,
			'Stash Size' => 'normal',
			),
		),
	'Bloodstone' => array(
		'types' => array('resource', 'land', 'stone'),
		'quals' => array(
			'scarcity' => 'high',
			'seasonal' => 1011, // no summer
			'renewable' => true,
			'Stash Size' => 'small',
			),
		),
	);
?>
