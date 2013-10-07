<?php namespace Atrauzzi\Tabular;

use SplFileObject;
//use ArrayAccess;

// https://github.com/symfony/symfony/tree/master/src/Symfony/Component/Translation/Loader/CsvFileLoader.php
// http://stackoverflow.com/questions/10181054/process-csv-into-array-with-column-headings-for-key

class Csv extends SplFileObject /* implements ArrayAccess */ {

	/**
	 * Will contain either a true-y value prior to first read, or an array containing all the column heading names.
	 * @var mixed
	 */
	protected $columns;

	/**
	 * Creates a new instance of the CSV reader.
	 *
	 * Note: http://www.php.net/manual/en/splfileobject.setcsvcontrol.php
	 *
	 * @param $file
	 * @param bool $columnIndexes
	 */
	public function __construct($file, $columnIndexes = true) {

		parent::__construct($file);
		$this->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);

		// By setting to a true-y value, column indexes will be generated on every rewind.
		$this->columns = $columnIndexes;

	}

	/**
	 * Detect column heading behaviour.
	 */
	public function rewind() {

		parent::rewind();

		if($this->columns) {
			$this->columns = parent::current();
			parent::next();
		}

	}

	/**
	 * @return array
	 */
	public function current() {
		return array_combine($this->columns, parent::current());
	}

	/**
	 * @return array
	 */
	public function getColumns() {
		return $this->columns;
	}

	/**
	 * Gets the numeric index belonging to a specific column.
	 *
	 * @param $name
	 * @return mixed
	 */
	public function getColumnIndex($name) {
		return $this->columns[$name];
	}

}