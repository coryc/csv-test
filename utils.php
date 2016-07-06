<?php
/**
 * Utility helper functions
 */

$names = array(
	array('Name_Id__c'=> '1',  'Name' => 'Caden'),
	array('Name_Id__c'=> '2',  'Name' => 'Sophia'),
	array('Name_Id__c'=> '3',  'Name' => 'Jackson'),
	array('Name_Id__c'=> '4',  'Name' => 'Emma'),
	array('Name_Id__c'=> '5',  'Name' => 'Aiden'),
	array('Name_Id__c'=> '6',  'Name' => 'Olivia'),
	array('Name_Id__c'=> '7',  'Name' => 'Ava'),
	array('Name_Id__c'=> '8',  'Name' => 'Liam'),
	array('Name_Id__c'=> '9',  'Name' => 'Lucas'),
	array('Name_Id__c'=> '10', 'Name' => 'Noah'),
	array('Name_Id__c'=> '11', 'Name' => 'Isabella'),
	array('Name_Id__c'=> '12', 'Name' => 'Mia'),
	array('Name_Id__c'=> '13', 'Name' => 'Zoe'),
	array('Name_Id__c'=> '14', 'Name' => 'Mason'),
	array('Name_Id__c'=> '15', 'Name' => 'Ethan'),
	array('Name_Id__c'=> '16', 'Name' => 'Lily'),
	array('Name_Id__c'=> '17', 'Name' => 'Emily'),
	array('Name_Id__c'=> '18', 'Name' => 'Jacob'),
	array('Name_Id__c'=> '19', 'Name' => 'Logan'),
	array('Name_Id__c'=> '20', 'Name' => 'Madelyn'),
);

// Filesize units
$units = array('b', 'kb', 'mb', 'gb', 'tb', 'pb', 'eb', 'zb', 'yb');

function convertFilesize($size, $from, $to, $dec = false) {
	// convert from size to bytes
	$bytes = toBytes($size, $from);
	return fromBytes($bytes, $to);
}

function fromBytes($bytes, $to, $dec = false) {

	global $units;	
	
	// get the init
	if (($unit = array_search(strtolower($to), $units)) !== false) {
		$value = ($bytes / pow(1024, floor($unit)));
		return ($dec !== false && $dec > 0) ? number_format($value, $dec) : $value;
	}

	return null;
}

function toBytes($size, $unit) {

	global $units;

	if (($index = array_search(strtolower($unit), $units)) !== false) {
		return $size * pow(1024, $index);
	}

	return null;
}