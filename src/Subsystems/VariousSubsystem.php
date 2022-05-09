<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Subsystems;

use DateTime;
use JuniWalk\Wedos\Response;

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
	 * @return Response
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-credit-info/
	 */
	public function creditInfo(): Response
	{
		return $this->call('credit-info');
	}


	/**
	 * @return Response
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-poll-req/
	 */
	public function pollRequest(): Response
	{
		return $this->call('poll-req');
	}


	/**
	 * @param  int  $id
	 * @return Response
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-command-poll-ack/
	 */
	public function pollAcknowledge(int $id): Response
	{
		return $this->call('poll-ack', [
			'id' => $id
		]);
	}


	/**
	 * @param  DateTime  $dateFrom
	 * @param  DateTime  $dateTo
	 * @return Response
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-account-list/
	 */
	public function accountList(DateTime $dateFrom, DateTime $dateTo): Response
	{
		return $this->call('account-list', [
			'date_from' => $dateFrom->format('Y-m-d'),
			'date_to' => $dateTo->format('Y-m-d'),
		]);
	}
}
