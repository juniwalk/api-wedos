<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos;

use GuzzleHttp\Client;
use JuniWalk\Wedos\Exceptions\ResponseException;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\ValidationException;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class Connector
{
	use Subsystems\DomainSubsystem;

	/** @var string */
	const URL = 'https://api.wedos.com/wapi/json';

	/** @var string */
	private $user;

	/** @var string */
	private $secret;

	/** @var bool */
	private $isTest;

	/** @var Client */
	private $http;


	/**
	 * @param  string  $user
	 * @param  string  $password
	 * @param  string  $isTest
	 * @param  string[]  $params
	 */
	public function __construct(
		string $user,
		string $password,
		bool $isTest = false,
		iterable $params = []
	) {
		$this->user = $user;
		$this->secret = $this->hash($user, $password);
		$this->isTest = $isTest;
		$this->http = new Client($params + [
			'base_uri' => static::URL,
			'timeout' => 5
		]);
	}


	/**
	 * @param  string  $action
	 * @param  string[]  $data
	 * @return string[]
	 * @throws ClientException
	 * @throws ResponseException
	 */
	protected function call(string $action, iterable $data = []): iterable
	{
		try {
			$response = $this->http->request('POST', '/', [
				'form_params' => ['request' => [
					'user' => $this->user,
					'auth' => $this->secret,
					'test' => $this->isTest,
					'command' => $action,
					// 'clTRID' => $clTRID,
					'data' => $data,
				]],
			]);

		} catch (ClientException $e) {
			throw $e;
		}

		$result = $response->getBody()->getContents();
		$result = Json::decode($result, Json::FORCE_ARRAY);

		return $result;
	}


	/**
	 * @param  string[]  $params
	 * @param  Expect[]  $schema
	 * @return string[]
	 * @throws ValidationException
	 */
	protected function check(iterable $params, iterable $schema): iterable
	{
		$schema = Expect::structure($schema)
			->skipDefaults()
			->castTo('array');

		try {
			$params = (new Processor)->process($schema, $params);

		} catch (ValidationException $e) {
			throw $e;
		}

		return $params;
	}


	/**
	 * @param	string	$user
	 * @param	string	$password
	 * @return	string
	 */
	protected function hash(string $user, string $password): string
	{
		return sha1($user.sha1($password).date('H'));
	}
}
