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
	protected int $code;
	protected string $result;
	protected int $timestamp;
	protected string $clTRID;
	protected string $svTRID;
	protected string $command;
	protected bool $test = false;
	protected ?stdClass $data;

	public function __construct(
		protected Request $request,
	) {
	}


	/**
	 * @throws ResponseException
	 */
	public static function fromResult(Request $request, string $result): self
	{
		try {
			/** @var object{response?: object{code: int, result: string}} $json */
			$json = Json::decode($result);

		} catch (JsonException $e) {
			throw ResponseException::fromError($request->getCommand(), $e);
		}

		if (!isset($json->response) || $json->response->code >= 2000) {
			throw ResponseException::fromResponse(
				$request->getCommand(),
				$json->response ?? null,
			);
		}

		$response = new self($request);

		foreach ((array) $json->response as $key => $value) {
			$response->$key = $value;
		}

		return $response;
	}


	public function getCode(): int
	{
		return $this->code;
	}


	public function getResult(): string
	{
		return $this->result;
	}


	public function getQueryId(): string
	{
		return $this->clTRID;
	}


	public function getCommand(): string
	{
		return $this->command;
	}


	public function getData(): ?stdClass
	{
		return $this->data;
	}


	public function isTest(): bool
	{
		return (bool) $this->test;
	}


	public function isOk(): bool
	{
		return $this->result === 'OK';
	}
}
