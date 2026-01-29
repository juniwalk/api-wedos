<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos\Exceptions;

use CurlHandle;

final class RequestException extends AbstractException
{
	public static function fromCurl(string $action, CurlHandle|false $curl): self
	{
		$message = 'Unknown';
		$code = 0;

		if ($curl !== false) {
			$message = curl_error($curl);
			$code = curl_errno($curl);
		}

		return new static($action.': '.$message, $code);
	}


	public static function fromOption(string $option): self
	{
		return new static('No CURL option named "'.$option.'" is defined');
	}
}
