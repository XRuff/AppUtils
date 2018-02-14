<?php

namespace XRuff\App\Model\Utils;

use Nette\Database\Context;
use Nette\Security\User;
use Nette\SmartObject;
use Nette\Utils\DateTime;

/**
 * Logger
 *
 * @author		Pavel Lauko <info@webengine.cz>
 * @package		Core
 */
class Logger
{
    use SmartObject;

	const TABLE_NAME = 'log';
	const COLUMN_ID = 'id';

	/* @var Context $database */
	private $database;

	/* @var User $user */
	private $user;

	/* @var int $domainId */
	private $domainId;

	/* @var array $onLog */
	public $onLog = [];

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

		if ($this->domainId) {
			$values['domain_id'] = $this->domainId;
		}

		$logged = $this->database->table(self::TABLE_NAME)->insert($values);
		$this->onLog($logged);
		return $logged;
	}

	public function setDomainId($domainId)
	{
		$this->domainId = $domainId;
		return $this;
	}

}
