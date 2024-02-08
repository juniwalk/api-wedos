<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Exceptions;

final class RequestException extends AbstractException
{
	/**
	 * @param  string  $action
	 * @param  resource  $curl
	 * @return static
	 */
	public static function fromCurl(string $action, $curl): self
	{
		return new static($action.': '.curl_error($curl), curl_errno($curl));
	}


	/**
	 * @param  string  $option
	 * @return static
	 */
	public static function fromOption(string $option): self
	{
		return new static('No CURL option named "'.$option.'" is defined');
	}
}
