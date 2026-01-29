<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Subsystems;

use JuniWalk\Wedos\Response;

trait DomainSubsystem
{
	/**
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-domain-info/
	 */
	public function domainInfo(string $name): Response
	{
		return $this->call('domain-info', [
			'name' => $name,
		]);
	}


	/**
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-domain-renew/
	 */
	public function domainRenew(string $name, int $period = 1): Response
	{
		return $this->call('domain-renew', [
			'name' => $name,
			'period' => $period,
		]);
	}


	/**
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-domains-list/
	 */
	public function domainsList(?string $status = null): Response
	{
		return $this->call('domains-list', [
			'status' => $status,
		]);
	}
}
