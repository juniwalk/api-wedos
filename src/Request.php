<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos;

use JuniWalk\Wedos\Exceptions\RequestException;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Random;
use Nette\Utils\Strings;

/**
 * @phpstan-type RequestData array<string, string|int|null>
 */
class Request
{
	protected string $clTRID;
	protected bool $isTest = false;

	/**
	 * @param array<string, string> $params
	 */
	public function __construct(
		protected string $user,
		protected string $auth,
		protected string $command,
		protected string $url,
		protected array $params = [],
	) {
		$this->clTRID = Random::generate();
	}


	public function getQueryId(): string
	{
		return $this->clTRID;
	}


	public function getCommand(): string
	{
		return $this->command;
	}


	public function setTest(bool $test): void
	{
		$this->isTest = $test;
	}


	public function isTest(): bool
	{
		return $this->isTest;
	}


	/**
	 * @param  RequestData $data
	 * @throws JsonException
	 * @throws RequestException
	 */
	public function execute(array $data): string
	{
		$query = Json::encode(['request' => [
			'user' => $this->user,
			'auth' => $this->auth,
			'test' => $this->isTest,
			'command' => $this->command,
			'clTRID' => $this->clTRID,
			'data' => $data,
		]]);

		$curl = curl_init($this->url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, 'request='.urlencode($query));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 100);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		foreach ($this->params as $key => $value) {
			$opt = 'CURLOPT_'.Strings::upper($key);

			if (!defined($opt)) {
				throw RequestException::fromOption($key);
			}

			curl_setopt($curl, constant($opt), $value);	// @phpstan-ignore argument.type (Ignore type errors for the setopt functions)
		}

		$result = curl_exec($curl);

		if (!is_string($result) || curl_errno($curl) > 0) {
			throw RequestException::fromCurl($this->command, $curl);
		}

		return $result;
	}
}
