<?php

namespace XRuff\App\Model\Utils\Repositories;

/**
 * Domain Repository
 */
class DomainRepository extends BaseRepository {

	/** @var int $domainId */
	private $domainId;

	/** @var string $host */
	private $host;

	/** @var string $host */
	private $domain;

	public function getDomainByHost($host)
	{
		if ($this->host === $host) {
			$this->setupDomain();
			return $this->domain;
		}

		$this->host = $host;

		$this->domain = $this->load($host);

		if ($this->domain) {
			$this->setupDomain();
		}

		return $this->domain;
	}

	private function load($host)
	{
		return $this->findBy(
			[
				'url LIKE ?' => '%' . $host . '%',
			]
		)->fetch();
	}

	private function setupDomain()
	{
		$this->domainId = $this->domain['id'];
	}

	public function getDomainId()
	{
		return $this->domainId;
	}
}
