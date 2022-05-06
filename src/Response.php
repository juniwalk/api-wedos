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

	/** @var stdClass|null */
	protected $data;


	/**
	 * @param Request  $request
	 * @param int  $code
	 * @param string  $result
	 * @param stdClass|null  $data
	 */
	public function __construct(Request $request, int $code, string $result, ?stdClass $data)
	{
		$this->request = $request;
		$this->code = $code;
		$this->result = $result;
		$this->data = $data;
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
		$result = Json::decode($result);
		$action = $request->getAction();

		if (!isset($result->response)) {
			throw ResponseException::withoutResponse($action);
		}

		$response = $result->response;
		$response = new static(
			$request,
			$response->code,
			$response->result,
			$response->data ?? null
		);

		switch ($response->getCode()) {
			case 1000:	// OK
			case 1001:	// Request pending
			case 1002:	// Notification aquired (accepted)
			case 1003:	// Empty notifications queue
			case 2151:	// Notification does not exist
				break;

			default:
				throw ResponseException::fromResponse($action, $response);
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
	public function getAction(): string
	{
		return $this->action;
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
	public function isOk(): bool
	{
		return $this->result === 'OK';
	}
}
