<?php
/**
 * Create a csv batches based on max filesize
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


class CreateBatch {

	/**
	 * Data
	 */
	private $_data = array();

	/**
	 * Batch array
	 */
	private $_batches = array();

	/**
	 * Constructor
	 * @return CreateBatch
	 */
	public function __construct(array $data) {
	
		$this->_data = $data;

		return $this;
	}

	/**
	 * Create Batches
	 */
	public function create() {

		$batchFile = new Batch($this->_data);
		$batch = $batchFile->create();

		$this->_batches[] = $batch;

		echo "\n--------\n";
		echo $batch;
		echo "--------\n\n";

		// get remaining stack
		$stack = $batchFile->getStack();
		print_r($stack);

		return $this->_batches;
	}

	public function getBatches(){
		return $this->_batches;
	}
}

class Batch {

	/**
	 * Max size in bytes
	 */
	const MAX_FILE_SIZE = 60;

	private $_data = array();

	private $_stack = array();

	private $_result = null;

	private $_fp;

	/**
	 * Constuctor
	 */
	public function __construct(array $data){

		$this->_data = $data;

		return $this;
	}

	/**
	 * Return the stack
	 */
	public function getStack() {
		return $this->_stack;
	}

	public function getResult() {
		return $this->_result;
	}

	/**
	 * Create the batch csv
	 */
	public function create() {

		// create the stack
		$this->_stack = $this->_data;

		if ($this->_open()) {

			$size = 0; 
			$pos = 0;
			$maxReached = false;

			// process the stack
			while (count($this->_stack) > 0) {
				// get the row
				$row = array_shift($this->_stack);
				
				// add the row to the csv file
				fputcsv($this->_fp, $row);

				$stat    = fstat($this->_fp);
				$size    = $stat['size'];
				$lastPos = $pos;
				$pos     = ftell($this->_fp);

				// check if the max size has been reached
				if ($size >= self::MAX_FILE_SIZE) {
					$maxReached = true;
					// add the row back on the stack
					array_unshift($this->_stack, $row);
					break;
				}
			}

			// remove the last line from the file
			if ($maxReached == true) {
				rewind($this->_fp);
				ftruncate($this->_fp, $lastPos);
			}

			//reset file pointer
			rewind($this->_fp);

			// get stream contents
			$this->_result = stream_get_contents($this->_fp);

			$this->_close();

			return $this->_result;

		} else {
			throw new Exception('Could not open file');
			return false;
		}

		return false;
	}	

	private function _open() {
		
		if (($fp = fopen('php://memory', 'w+')) !== false) {
			$this->_fp = $fp;
			return true;
		}

		return false;
	}

	private function _close() {
		fclose($this->_fp);
	}

}	

$createBatch = new CreateBatch($data);
$batches = $createBatch->create();

echo "Batches -> \n\n";
print_r($batches);
echo "\n\n";