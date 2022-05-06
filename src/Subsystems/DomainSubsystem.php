<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Subsystems;

use JuniWalk\Wedos\Response;
use Nette\Schema\Expect;

trait DomainSubsystem
{
	/**
	 * @param  string  $name
	 * @param  string[]  $params
	 * @return Response
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-domain-info/
	 */
	public function domainInfo(string $name, iterable $params = []): Response
	{
		$params['name'] = $name;
		$params = $this->check($params, [
			'name'	=> Expect::string()->required(),
		]);

		return $this->call('domain-info', '', $params);
	}


	/**
	 * @param  string  $name
	 * @param  int  $period
	 * @param  string[]  $params
	 * @return Response
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-domain-renew/
	 */
	public function domainRenew(string $name, int $period = 1, iterable $params = []): Response
	{
		$params['name'] = $name;
		$params['period'] = $period;
		$params = $this->check($params, [
			'name'		=> Expect::string()->required(),
			'period'	=> Expect::int()->required(),
		]);

		return $this->call('domain-renew', '', $params);
	}


	/**
	 * @param  string|null  $status
	 * @param  string[]  $params
	 * @return Response
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-domains-list/
	 */
	public function domainsList(string $status = null, iterable $params = []): Response
	{
		$params['status'] = $status;
		$params = $this->check($params, [
			'status'	=> Expect::string()->nullable(),
		]);

		return $this->call('domains-list', '', $params);
	}
}
