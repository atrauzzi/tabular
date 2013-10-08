<?php namespace Atrauzzi\Tabular;

use SplFileObject;


class Csv extends SplFileObject {

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

		// A failure to read a row doesn't return anything.
		if($row = parent::current()) {
			if($this->columns)
				return array_combine($this->columns, $row);
			else
				return $row;
		}

	}

	/**
	 * Returns the label of a specific column (if it exists).
	 *
	 * @param $index
	 * @return string
	 */
	public function getColumnName($index) {
		if(isset($this->columns[$index]))
			return $this->columns[$index];
	}

	/**
	 * Gets an array with all column titles.
	 *
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