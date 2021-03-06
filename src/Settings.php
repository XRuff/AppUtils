<?php

namespace XRuff\App\Model\Utils;

use Nette\Database\Context;
use Nette\SmartObject;
use XRuff\App\Model\Utils\Caching\Cache;

/**
 * Settings model
 *
 * @author		Pavel Lauko <info@webengine.cz>
 * @package		Core
 */
class Settings
{
    use SmartObject;

	const TABLE_NAME = 'settings';
	const COLUMN_ID = 'id';
	const COLUMN_GROUP = 'group';
	const COLUMN_DOMAIN = 'domain_id';

	/** @var Cache */
	private $cache;

	/** @var Context */
	private $database;

	/**
	 * @param Context $database
	 * @param Cache $cache
	 */
	public function __construct(Context $database, Cache $cache)
	{
		$this->database = $database;
		$this->cache = $cache;
	}

	/**
	 * @param int $domainId
	 * @return Nette\Database\Table\Selection
	 */
	private function getByDomain($domainId)
	{
		return $this->database->table(self::TABLE_NAME)->where([self::COLUMN_DOMAIN => $domainId]);
	}

	/**
	 * @param string $group
	 * @param int $domainId
	 * @return Nette\Database\Table\Selection
	 */
	public function getByGroup($group, $domainId)
	{
		return $this->database
			->table(self::TABLE_NAME)
			->where([self::COLUMN_DOMAIN => $domainId])
			->where([self::COLUMN_GROUP => $group]);
	}

	/**
	 * @param int $domainId
	 * @return XRuff\App\Model\Utils\Sett
	 */
	public function getSettings($domainId)
	{
		$self = $this;
		$key = 'settings_' . $domainId;
		$settings = $this->cache->load($key, function () use ($self, $key, $domainId) {
			$settingsByDomain = $self->getByDomain($domainId)->fetchPairs('key', 'value');
			$settings = new Sett();
			foreach ($settingsByDomain as $property => $value) {
				$settings->{$property} = $value;
			}

			$self->cache->save($key, $settings, [
				Cache::TAGS => ['settings', $key],
			]);

			return $settings;
		});

		return $settings;
	}

	/**
	 *
	 * @param Nette\ArrayHash $values
	 * @return Nette\Database\Table\IRow|integer|boolean
	 */
	public function add($values)
	{
		$this->cache->clean();

		unset($values['id']);
		return $this->database->table(self::TABLE_NAME)->insert($values);
	}

	/**
	 *
	 * @param Nette\ArrayHash $values
	 * @return int
	 */
	public function edit($values)
	{
		$this->cache->clean();

		return $this->database
						->table(self::TABLE_NAME)
						->where(self::COLUMN_ID, $values['id'])
						->update($values);
	}

}

class Sett extends \StdClass
{

}
