<?php

namespace XRuff\App\Model\Utils\Caching;

use Nette;
use Nette\Caching;

final class Cache extends Caching\Cache
{
	/**
	 * Remove all items cached by extension
	 *
	 * @param array $conditions
	 *
	 * @return void
	 */
	public function clean(array $conditions = NULL)
	{
		parent::clean([self::TAGS => ['settings']]);
	}
}
