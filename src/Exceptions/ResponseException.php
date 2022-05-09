<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Exceptions;

use stdClass;

final class ResponseException extends AbstractException
{
	/**
	 * @param  string  $command
	 * @param  stdClass  $response
	 * @return static
	 */
	public static function fromResponse(string $command, ?stdClass $response): self
	{
		$message = $response->result ?? 'Response from the result is missing';
		$code = $response->code ?? null;

		return new static($command.': '.$message, $code);
	}
}
