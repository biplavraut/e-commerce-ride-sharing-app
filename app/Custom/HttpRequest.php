<?php

namespace App\Custom;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class HttpRequest
{
	private $url;
	private $parameters;
	private $method;
	private $headers = [];
	private $response = [];
	private $statusCode;

	/**
	 * @param array $headers
	 *
	 * @return HttpRequest
	 */
	public function setHeaders($headers): HttpRequest
	{
		$this->headers = $headers;

		return $this;
	}

	/**
	 * @param string $url
	 * @param array $parameters
	 *
	 * @return $this
	 */
	public function get($url, $parameters = [])
	{
		$this->method = 'GET';
		$this->url    = $parameters
			? $url . '?' . http_build_query($parameters)
			: $url;

		return $this;
	}

	/**
	 * @param $url
	 * @param $parameters
	 *
	 * @return $this
	 */
	public function post($url, $parameters = []): HttpRequest
	{
		$this->method     = 'POST';
		$this->url        = $url;
		$this->parameters = http_build_query($parameters);

		return $this;
	}

	/**
	 * @throws Exception
	 */
	public function execute(): HttpRequest
	{
		$ch = curl_init($this->url);
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		if ($this->method === 'POST') {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->parameters);
		}

		if (count($this->headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		}

		// Execute
		$this->response   = curl_exec($ch);
		$this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (curl_errno($ch)) {
			throw new Exception('Something went wrong. Please try again later.', $this->statusCode);
		}

		// Closing
		curl_close($ch);

		return $this;
	}

	/**
	 * Convert array to string url params
	 *
	 * @param array $parameters
	 *
	 * @return string
	 */
	private function formatGetMethodParameters($parameters): string
	{
		$formatted = '';
		foreach ($parameters as $key => $value) {
			$formatted .= "{$key}={$value}&";
		}

		// removing last char(&) from the string
		return substr($formatted, 0, - 1);
	}

	public function response(): array
	{
		return json_decode($this->response, true);
	}

	public function statusCode(): int
	{
		return $this->statusCode;
	}

	public function isOk(): bool
	{
		return $this->statusCode() === Response::HTTP_OK;
	}
}