<?php

namespace XRuff\App\Model\Utils;

use Nette\Database\Context;
use Nette\Object;
use Nette\Security\User;
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

	/* @var User $user */
	private $user;

	/**
	 * @param Context $database
	 * @param User $user
	 */
	public function __construct(Context $database, User $user)
	{
		$this->database = $database;
		$this->user = $user;
	}

	/**
	 * @param string $desc
	 * @param string $type
	 * @param int $status
	 * @param int $visibility
	 */
	public function log(
		$desc,
		$type = 'info',
		$status = 1,
		$visibility = 1
	) {
		$values = [
			'users_id' => $this->user->id,
			'type' => $type,
			'description' => $desc,
			'status' => $status,
			'visibility' => $visibility,
			'date_added' => new DateTime(),
		];
		return $this->database->table(self::TABLE_NAME)->insert($values);
	}

}
