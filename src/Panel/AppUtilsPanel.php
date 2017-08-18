<?php

namespace XRuff\App\Model\Utils\Panel;

use Nette;
use Nette\Http\IRequest;
use Tracy;
use XRuff\App\Model\Utils\Logger;
use XRuff\App\Model\Utils\Repositories\DomainRepository;
use XRuff\App\Model\Utils\Settings;

/**
 * Debug panel.
 */
class AppUtilsPanel implements Tracy\IBarPanel
{
	use Nette\SmartObject;

	/** @var string */
	public $name;

	/** @var bool */
	public $disabled = false;

	/** @var Settings $settings */
	private $settings;

	/** @var int */
	private $domainId;

	/** @var array $logEntries */
	private $logEntries = [];

	public function __construct(IRequest $request, DomainRepository $domains, Logger $logger, Settings $settings)
	{
		$this->settings = $settings;

		$logger->onLog[] = [$this, 'addLogEntry'];

		if (!$domains->getDomainId()) {
			$domains->getDomainByHost($request->getUrl()->getHost());
		}
		$this->domainId = $domains->getDomainId();
	}

	public function addLogEntry($logEntry)
	{
		if ($this->disabled) {
			return;
		}
		$this->logEntries[] = $logEntry;
	}

	private function loadSettings()
	{
		return $this->settings->getSettings($this->domainId);
	}

	public function getTab()
	{
		$name = $this->name;
		$domainId = $this->domainId;
		ob_start(function () {});
		require __DIR__ . '/templates/AppUtilsPanel.tab.phtml';
		return ob_get_clean();
	}

	public function getPanel()
	{
		$this->disabled = true;

		$name = $this->name;
		$domainId = $this->domainId;
		$logEntries = $this->logEntries;
		$settings = $this->loadSettings();

		ob_start(function () {});
		require __DIR__ . '/templates/AppUtilsPanel.panel.phtml';
		return ob_get_clean();
	}
}
