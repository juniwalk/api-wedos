<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos;

use JuniWalk\Wedos\Exceptions\ResponseException;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use stdClass;

class Response
{
	/** @var Request */
	protected $request;

	/** @var int */
	protected $code;

	/** @var string */
	protected $result;

	/** @var int */
	protected $timestamp;

	/** @var string */
	protected $clTRID;

	/** @var string */
	protected $svTRID;

	/** @var string */
	protected $command;

	/** @var bool */
	protected $test = false;

	/** @var stdClass|null */
	protected $data;


	/**
	 * @param Request  $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}


	/**
	 * @param  Request  $request
	 * @param  string  $result
	 * @return self
	 * @throws JsonException
	 * @throws ResponseException
	 */
	public static function fromResult(Request $request, string $result): self
	{
		$response = new static($request);
		$result = Json::decode($result);

		if (!isset($result->response) || $result->response->code >= 2000) {
			throw ResponseException::fromResponse(
				$request->getCommand(),
				$result->response ?? null
			);
		}

		foreach ((array) $result->response as $key => $value) {
			$response->$key = $value;
		}

		return $response;
	}


	/**
	 * @return int
	 */
	public function getCode(): int
	{
		return $this->code;
	}


	/**
	 * @return string
	 */
	public function getResult(): string
	{
		return $this->result;
	}


	/**
	 * @return string
	 */
	public function getQueryId(): string
	{
		return $this->clTRID;
	}


	/**
	 * @return string
	 */
	public function getCommand(): string
	{
		return $this->command;
	}


	/**
	 * @return stdClass|null
	 */
	public function getData(): ?stdClass
	{
		return $this->data;
	}


	/**
	 * @return bool
	 */
	public function isTest(): bool
	{
		return (bool) $this->test;
	}


	/**
	 * @return bool
	 */
	public function isOk(): bool
	{
		return $this->result === 'OK';
	}
}
