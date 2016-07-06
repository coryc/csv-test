<?php
/**
 * combine all tests into this one, set a max filesize, 
 * if the csv exceeds that limit then remove the last record
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

	foreach ($data as $i => $row) {
		fputcsv($fp, $row);

		$stat     = fstat($fp);
		$prevSize = $size;
		$size     = $stat['size'];
		$lineSize = $size - $prevSize;
		$lastPos = $pos;
		$pos = ftell($fp);

		echo "Row $i :: Total Size: $size - Line Size: $lineSize - Pos: $pos\n";

		// stop adding more records if the max size was reached
		if ($size >= MAX_FILE_SIZE) {
			$maxReached = true;
			break;
		}
	}

	//reset file pointer
	rewind($fp);

	// get stream contents
	$csv = stream_get_contents($fp);

	echo "\n--------\n";
	echo $csv;
	echo "--------\n\n";

	if ($maxReached == true) {
		echo "Removing record from the file:\n";

		rewind($fp);
		ftruncate($fp, $lastPos);
		$csv = stream_get_contents($fp);

		echo "\n--------\n";
		echo $csv;
		echo "--------\n\n";

		$stat = fstat($fp);
		echo "Final File Size: $stat[size] \n\n";
	}

	fclose($fp);
} else {
	die('Could not allocate memory');
}