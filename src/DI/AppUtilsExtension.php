<?php

namespace XRuff\App\Model\Utils\DI;

use Nette;
use XRuff\App\Model\Utils\Caching;

class AppUtilsExtension extends Nette\DI\CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		$builder->addDefinition($this->prefix('cache'))
			->setClass(Caching\Cache::class, ['@cacheStorage', 'XRuff.AppUtils'])
			->setInject(false);

		$builder->addDefinition($this->prefix('email'))
			->setClass('XRuff\App\Model\Utils\Email')
			->setInject(false);

		$builder->addDefinition($this->prefix('logger'))
			->setClass('XRuff\App\Model\Utils\Logger')
			->setInject(false);

		$builder->addDefinition($this->prefix('settings'))
			->setClass('XRuff\App\Model\Utils\Settings')
			->setInject(false);

		$builder->addDefinition($this->prefix('repository.domain'))
			->setClass('XRuff\App\Model\Utils\Repositories\DomainRepository')
			->setInject(false);
	}

	/**
	 * @param \Nette\Configurator $configurator
	 */
	public static function register(Nette\Configurator $configurator)
	{
		$configurator->onCompile[] = function ($config, Nette\DI\Compiler $compiler) {
			$compiler->addExtension('apputils', new AppUtilsExtension());
		};
	}

}
