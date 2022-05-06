<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos;

use Exception;
use JuniWalk\Wedos\Exceptions\RequestException;
use JuniWalk\Wedos\Exceptions\ResponseException;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\ValidationException;

class Connector
{
	use Subsystems\DomainSubsystem;
	use Subsystems\VariousSubsystem;

	/** @var string */
	const URL = 'https://api.wedos.com/wapi/json';

	/** @var string */
	protected $user;

	/** @var string */
	protected $secret;

	/** @var bool */
	protected $isTest;

	/** @var mixed[] */
	protected $config;


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
		$this->config = $params;
	}


	/**
	 * @param  string  $action
	 * @param  string[]  $data
	 * @return Response
	 * @throws JsonException
	 * @throws ResponseException
	 */
	protected function call(string $action, iterable $data = []): Response
	{
		$request = new Request($action, static::URL, $this->config);

		try {
			$result = $request->execute([
				'user' => $this->user,
				'auth' => $this->secret,
				'test' => $this->isTest,
				'data' => $data,
			]);

		} catch (RequestException $e) {
			throw $e;
		}

		return Response::fromResult($result, $request);
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
