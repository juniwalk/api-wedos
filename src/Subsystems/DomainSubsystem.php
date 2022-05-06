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
}
