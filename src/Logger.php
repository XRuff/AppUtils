<?php

namespace XRuff\App\Model\Utils;

use Nette\Object;
use Nette\Database\Context;
use Nette\Utils\DateTime;

class Logger extends Object {

	const
		TABLE_NAME = 'log',
		COLUMN_ID = 'id';

	/* @var Context $database */
	private $database;

	/**
	 * @param Context $database
	 */
	public function __construct(Context $database) {
		$this->database = $database;
	}

	/**
	 * @param string $desc
	 * @param int|NULL $user
	 * @param string $type
	 * @param int $status
	 * @param int $visibility
	 */
	public function log(
		$desc,
		$user = NULL,
		$type = 'info',
		$status = 1,
		$visibility = 1

	) {
		$values = array(
			'users_id' => $user,
			'type' => $type,
			'description' => $desc,
			'status' => $status,
			'visibility' => $visibility,
			'date_added' => new DateTime(),
		);
		return $this->database->table(self::TABLE_NAME)->insert($values);
	}

}