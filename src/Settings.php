<?php

namespace XRuff\App\Model\Utils;

use	Nette\Database\Context;
use	Nette\Caching\Cache;
use	Nette\Object;

use	Tracy\Debugger;

use	App\Model\BaseDbManager;


/**
 * Settings model
 *
 * @author		Pavel Lauko
 * @package		Core
 */
class Settings extends Object {

	const
		TABLE_NAME = 'settings',
		COLUMN_ID = 'id',
		COLUMN_DOMAIN = 'domain_id';

	/** @var Cache */
	private $cache;

	/** @var Context */
	private $database;

	/*
	* @param Context $database
	* @param Cache $cache
	*/
	public function __construct(Context $database, Cache $cache) {
		$this->database = $database;
		$this->cache = $cache;
	}

	/*
	* @param int $domainId
	* @return Nette\Database\Table\Selection
	*/
	private function getByDomain($domainId) {
		return $this->database->table(self::TABLE_NAME)->where( array(self::COLUMN_DOMAIN => $domainId) );
	}

	/*
	* @param int $domainId
	* @return XRuff\App\Model\Utils\Sett
	*/
	public function getSettings($domainId) {
		$self = $this;
		$key = 'settings_'. $domainId;
		$settings = $this->cache->load($key, function() use ($self, $key, $domainId) {
			$settingsByDomain = $self->getByDomain ($domainId)->fetchPairs('key', 'value');
			$settings = new Sett();
			foreach ($settingsByDomain as $key => $value) {
				$settings->{$key} = $value;
			}

			$self->cache->save($key, $settings, array(
				Cache::TAGS => array("settings", $key),
			));

			return $settings;
		});

		return $settings;
	}

	/**
	 *
	 * @param Nette\ArrayHash $values
	 * @return Nette\Database\Table\IRow|integer|boolean
	 */
	public function add($values) {

		$this->cache->clean(array(
			Cache::TAGS => array('settings'),
		));

		unset($values['id']);
		return $this->database->table(self::TABLE_NAME)->insert($values);
	}

	/**
	 *
	 * @param Nette\ArrayHash $values
	 * @return int
	 */
	public function edit($values) {

		$this->cache->clean(array(
			Cache::TAGS => array('settings'),
		));

		return $this->database
						->table(self::TABLE_NAME)
						->where(self::COLUMN_ID, $values['id'])
						->update($values);
	}

}

class Sett extends \StdClass /* \Nette\Object */ {

}
