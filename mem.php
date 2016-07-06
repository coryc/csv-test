<?php
/**
 * Testing csv memory stream
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


if (($fp = fopen('php://memory', 'w+')) !== false) {

	foreach ($data as $row) {
		print_r($row);
		fputcsv($fp, $row);
	}

	//reset file pointer
	rewind($fp);

	// get stream contents
	$csv = stream_get_contents($fp);

	echo "\n--------\n";
	echo $csv;
	echo "--------\n\n";

	fclose($fp);
} else {
	die('Could not allocate memory');
}