<?php

namespace App\Custom;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class LogData
{
	private $fileName;

	public $date;
	public $message;

	/**
	 * LogData constructor.
	 *
	 * @param string $fileName
	 */
	public function __construct(string $fileName)
	{
		$this->fileName = $fileName;
	}

	/**
	 * Set file name after storage/logs/
	 *
	 * @param string $fileName
	 *
	 * @return LogData
	 */
	public function setFileName(string $fileName): LogData
	{
		$this->fileName = $fileName;

		return $this;
	}

	/**
	 * Get the contents of file as date and message in the form of object collection
	 *
	 * @return Collection
	 */
	public function get(): Collection
	{
		$logData = $this->getFileContentsAsArray();

		$logCollection = [];
		foreach ($logData as $lineNum => $line) {
			$logCollection[] = $this->instantiate($line);
		}

		return collect($logCollection);
	}

	/**
	 * Get carbon instance of date from line
	 *
	 * @param string $line
	 *
	 * @return Carbon
	 */
	private function parseDate(string $line): Carbon
	{
		$date = str_replace(str_split('[]'), '', explode('local.INFO: ', htmlspecialchars($line))[0]);

		return Carbon::parse($date);
	}

	/**
	 * Get message from line
	 *
	 * @param string $line
	 *
	 * @return string
	 */
	private function parseMessage(string $line): string
	{
		return explode('local.INFO: ', $line)[1];
	}

	/**
	 * Change date and message into object
	 *
	 * @param string $line
	 *
	 * @return LogData
	 */
	private function instantiate(string $line): LogData
	{
		$row          = new self();
		$row->date    = $this->parseDate($line);
		$row->message = $this->parseMessage($line);

		return $row;
	}

	/**
	 * Get contents of file as array of lineNumber(key) and content(value)
	 * On failure return empty array
	 *
	 * @return array
	 */
	private function getFileContentsAsArray(): array
	{
		return file(storage_path() . '/logs/' . $this->fileName) ?: [];
	}
}