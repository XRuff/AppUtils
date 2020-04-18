<?php

namespace XRuff\App\Model\Utils\DI;

use Nette;
use Nette\DI\Extensions\InjectExtension;
use XRuff\App\Model\Utils;

class AppUtilsExtension extends Nette\DI\CompilerExtension
{
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('cache'))
			->setFactory(Utils\Caching\Cache::class, ['@cacheStorage', 'XRuff.AppUtils'])
			->addTag(InjectExtension::TAG_INJECT);

		$builder->addDefinition($this->prefix('email'))
			->setFactory(Utils\Email::class)
			->addTag(InjectExtension::TAG_INJECT);

		$builder->addDefinition($this->prefix('logger'))
			->setFactory(Utils\Logger::class)
			->addTag(InjectExtension::TAG_INJECT);

		$builder->addDefinition($this->prefix('settings'))
			->setFactory(Utils\Settings::class)
			->addTag(InjectExtension::TAG_INJECT);

		$builder->addDefinition($this->prefix('repository.domain'))
			->setFactory(Utils\Repositories\DomainRepository::class)
			->addTag(InjectExtension::TAG_INJECT);

		$builder->addDefinition($this->prefix('repository.settings'))
			->setFactory(Utils\Repositories\SettingsRepository::class)
			->addTag(InjectExtension::TAG_INJECT);
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
