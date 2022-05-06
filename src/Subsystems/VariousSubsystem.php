<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Subsystems;

use DateTime;
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
		$params = [];
		$params['id'] = $id;
		$params = $this->check($params, [
			'id'	=> Expect::int()->required(),
		]);

		return $this->call('poll-ack', '', $params);
	}


	/**
	 * @param  DateTime  $dateFrom
	 * @param  DateTime  $dateTo
	 * @return Response
	 * @see https://kb.wedos.com/en/wapi-api-interface/wapi-account-list/
	 */
	public function accountList(DateTime $dateFrom, DateTime $dateTo): Response
	{
		$params = [];
		$params['date_from'] = $dateFrom->format('Y-m-d');
		$params['date_to'] = $dateTo->format('Y-m-d');
		$params = $this->check($params, [
			'date_from'	=> Expect::string()->required(),
			'date_to'	=> Expect::string()->required(),
		]);

		return $this->call('account-list', '', $params);
	}
}
