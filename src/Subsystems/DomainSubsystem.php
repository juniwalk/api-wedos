<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Subsystems;

use Nette\Schema\Expect;

trait DomainSubsystem
{
	/**
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-ping/
	 */
	public function ping(): bool
	{
		$response = $this->call('ping');
		return $response['result'] === 'OK';
	}
}
