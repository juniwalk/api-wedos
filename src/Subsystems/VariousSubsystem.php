<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Subsystems;

use JuniWalk\Wedos\Response;
use Nette\Schema\Expect;

trait VariousSubsystem
{
	/**
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-ping/
	 */
	public function ping(): bool
	{
		return $this->call('ping')->isOk();
	}


	/**
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-credit-info/
	 */
	public function creditInfo(): Response
	{
		return $this->call('credit-info');
	}
}
