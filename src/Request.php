<?php declare(strict_types=1);

/**
 * @copyright Martin Procházka (c) 2022
 * @license   MIT License
 */

namespace JuniWalk\Wedos;

use JuniWalk\Wedos\Exceptions\RequestException;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Random;
use Nette\Utils\Strings;

class Request
{
	/** @var string */
	protected $url;

	/** @var string */
	protected $queryId;

	/** @var string */
	protected $action;

	/** @var string[] */
	protected $params = [];


	/**
	 * @param string  $action
	 * @param string  $url
	 * @param string[]  $params
	 */
	public function __construct(string $action, string $url, iterable $params = [])
	{
		$this->action = $action;
		$this->url = $url;
		$this->params = $params;
		$this->queryId = Random::generate();
	}


	/**
	 * @return string
	 */
	public function getAction(): string
	{
		return $this->action;
	}


	/**
	 * @return string
	 */
	public function getQueryId(): string
	{
		return $this->queryId;
	}


	/**
	 * @param  string  $action
	 * @param  string[]  $data
	 * @return string
	 * @throws RequestException
	 */
	public function execute(iterable $data): string
	{
		$data['clTRID'] = $this->queryId;
		$data['command'] = $this->action;
		$data = Json::encode(['request' => $data]);
		$data = 'request='.urlencode($data);

		$curl = curl_init($this->url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 100);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		foreach ($this->params as $key => $value) {
			$opt = 'CURLOPT_'.Strings::upper($key);

			if (!defined($opt)) {
				throw RequestException::fromOption($key);
			}

			curl_setopt($curl, constant($opt), $value);
		}

		$result = curl_exec($curl);

		if (!$result || curl_errno($curl)) {
			throw RequestException::fromCurl($curl);
		}

		return $result;
	}
}
