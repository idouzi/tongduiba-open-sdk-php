<?php

namespace Tongduiba\Open\Helper;

use \GuzzleHttp\Client;
use Tongduiba\Open\Config\HttpConfig;
use Tongduiba\Open\Config\EcommerceConfig;

class HttpHelper {
	private static $config;

	public static function setConfig($config = []) {
		self::$config = $config;
	}

	public static function getConfig() {
		return self::$config;
	}

	public static function buildRequestUrl($api, $files = [])
	{
		$config = self::getConfig();
		$baseUrl = isset($config['baseUrl']) ? $config['baseUrl'] : EcommerceConfig::ENV_PROXY_HOST;
		$baseUrl .= $api;
		return $baseUrl;
	}

	public static function buildHttpHeaders()
	{
		$config = self::getConfig();
		return [
			'User-Agent' => sprintf(HttpConfig::API_USER_AGENT, EcommerceConfig::SDK_VERSION,
				isset($config['API_VERSION']) ? $config['API_VERSION'] : ''),
		];
	}

	public static function getUrl($url, $params = []) {
		$params = EcommerceHelper::setDefaultParams($params);
		$urlAndHeaders = EcommerceHelper::buildUrlAndHeaders($url, $params);
		return $urlAndHeaders['url'];
	}

	public static function get($url, $params = [])
	{
		$params = EcommerceHelper::setDefaultParams($params);
		$client = new Client();
		$urlAndHeaders = EcommerceHelper::buildUrlAndHeaders($url, $params);
		$response = $client->request(
			'GET',
			$urlAndHeaders['url'],
			self::buildOptional($params, null, $urlAndHeaders['headers'])
		);
		return $response->getBody()->getContents();
	}

	public static function post($url, $params = [], $files = [])
	{
		$params = EcommerceHelper::setDefaultParams($params);
		$client = new Client();
		$urlAndHeaders = EcommerceHelper::buildUrlAndHeaders($url, $params);
		$response = $client->request(
			'POST',
			$urlAndHeaders['url'],
			self::buildOptional($params, $files, $urlAndHeaders['headers'])
		);
		return $response->getBody()->getContents();
	}

	private static function buildOptional($params = [], $files = [], $headers = [])
	{
		$ret = [
			'headers' => array_merge(self::buildHttpHeaders(), $headers),
		];
		if (!empty($files)) {
			// 上传文件请求
			foreach ($files as $key => $file) {
				if (file_exists($file)) {
					$ret['multipart'][] = [
						'name' => $key,
						'contents' => fopen($file, 'r'),
					];
				}
			}
		} else {
			$ret['headers']['Content-Type'] = 'application/json';
			$ret['body'] = self::buildBody($params);
			return $ret;
		}
		return $ret;
	}

	private static function buildBody($params)
	{
		if (empty($params)) {
			return '{}';
		}
		return json_encode($params);
	}
}