<?php

namespace XRuff\App\Model\Utils;

use Nette\Database\Context;
use Nette\Object;
use Nette\Utils\DateTime;

/**
 * Logger
 *
 * @author		Pavel Lauko <info@webengine.cz>
 * @package		Core
 */
class Logger extends Object
{

	const TABLE_NAME = 'log';
	const COLUMN_ID = 'id';

	/* @var Context $database */
	private $database;

	/**
	 * @param Context $database
	 */
	public function __construct(Context $database)
	{
		$this->database = $database;
	}

	/**
	 * @param string $desc
	 * @param int|null $user
	 * @param string $type
	 * @param int $status
	 * @param int $visibility
	 */
	public function log(
		$desc,
		$user = null,
		$type = 'info',
		$status = 1,
		$visibility = 1
	) {
		$values = [
			'users_id' => $user,
			'type' => $type,
			'description' => $desc,
			'status' => $status,
			'visibility' => $visibility,
			'date_added' => new DateTime(),
		];
		return $this->database->table(self::TABLE_NAME)->insert($values);
	}

}
