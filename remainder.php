<?php
/**
 * create the csv file and track all the successfull records added to the file
 */

include_once ('utils.php');


$data = array(
	array(
		'f1' => 'Super',
		'f2' => 'Mario'
	),
	array(
		'f1' => 'Sonic',
		'f2' => 'The, Hedgehog'
	),
	array(
		'f1' => 'Harry',
		'f2' => 'Potter'
	),
	array(
		'f1' => 'Neo',
		'f2' => '"The One"'
	),
	array(
		'f1' => 'Luke',
		'f2' => 'Skywalker'
	)
);

// in bytes
define('MAX_FILE_SIZE', 60);

if (($fp = fopen('php://memory', 'w+')) !== false) {

	$size = 0; 
	$pos = 0;
	$maxReached = false;

	// work off the data stack
	$stack = $data;

	while(count($stack) > 0) {
		$row = array_shift($stack);
		fputcsv($fp, $row);

		$stat    = fstat($fp);
		$size    = $stat['size'];
		$lastPos = $pos;
		$pos     = ftell($fp);

		echo "size: $size \n";
		// stop adding more records if the max size was reached
		if ($size >= MAX_FILE_SIZE) {
			echo "max....";
			$maxReached = true;
			// add the row back on the stack
			array_unshift($stack, $row);
			break;
		}
	}

	// remove the last line from the file
	if ($maxReached == true) {
		echo "Removing record from the file:\n";

		rewind($fp);
		ftruncate($fp, $lastPos);
	}

	//reset file pointer
	rewind($fp);

	// get stream contents
	$csv = stream_get_contents($fp);

	echo "\n--------\n";
	echo $csv;
	echo "--------\n\n";

	print_r($stack);

	fclose($fp);
} else {
	die('Could not allocate memory');
}