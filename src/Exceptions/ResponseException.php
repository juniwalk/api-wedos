<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Exceptions;

use JuniWalk\Wedos\Response;

final class ResponseException extends AbstractException
{
	/**
	 * @param  string  $action
	 * @return static
	 */
	public static function withoutResponse(string $action): self
	{
		return new static($action.': Response from the result is missing');
	}


	/**
	 * @param  string  $action
	 * @param  Response  $response
	 * @return static
	 */
	public static function fromResponse(string $action, Response $response): self
	{
		return new static($action.': '.$response->getResult(), $response->getCode());
	}
}
