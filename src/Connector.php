<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos;

use JuniWalk\Wedos\Exceptions\RequestException;
use JuniWalk\Wedos\Exceptions\ResponseException;
use Nette\Utils\JsonException;

/**
 * @phpstan-import-type RequestData from Request
 */
class Connector
{
	use Subsystems\DomainSubsystem;
	use Subsystems\VariousSubsystem;

	/** @var string */
	const URL = 'https://api.wedos.com/wapi/json';

	protected string $secret;


	/**
	 * @param array<string, string> $config
	 */
	public function __construct(
		protected string $user,
		string $password,
		protected bool $isTest = false,
		protected array $config = [],
	) {
		$this->secret = $this->hash($user, $password);
	}


	/**
	 * @param  RequestData $data
	 * @throws JsonException
	 * @throws ResponseException
	 */
	public function call(string $command, array $data = []): Response
	{
		$request = new Request($this->user, $this->secret, $command, static::URL, $this->config);
		$request->setTest($this->isTest);

		try {
			$result = $request->execute($data);

		} catch (RequestException $e) {
			throw $e;
		}

		return Response::fromResult($request, $result);
	}


	protected function hash(string $user, string $password): string
	{
		return sha1($user.sha1($password).date('H'));
	}
}
