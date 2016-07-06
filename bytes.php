<?php
/**
 * Testing filesize conversion
 */

include_once ('utils.php');

echo '1111 bytes' . "\n\t -> ";
echo fromBytes(1111, 'mb') . ' MB';
echo "\n\n";


echo '0.0010595321655273 MB' . "\n\t -> ";
echo toBytes(0.0010595321655273, 'mb') . ' B';
echo "\n\n";

echo '1 GB to MB' . "\n\t -> ";
echo convertFilesize(1, 'gb', 'mb') . ' MB';
echo "\n\n";