<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Exceptions;

final class ResponseException extends AbstractException
{
	/**
	 * @param object{code: int, result: string} $response
	 */
	public static function fromResponse(string $command, ?object $response): self
	{
		$message = $response->result ?? 'Response from the result is missing';
		$code = $response->code ?? 0;

		return new static($command.': '.$message, $code);
	}
}
