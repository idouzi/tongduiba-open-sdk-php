<?php

namespace Tongduiba\Open;

use Tongduiba\Open\Helper\HttpHelper;
use Tongduiba\Open\Helper\EcommerceHelper;

class Client {
	private $appKey;//必填, 应用appKey, 应用后台获取该值
	private $appSecret;//必填, 应用appSecret, 应用后台获取该值
	public function __construct($appKey, $appSecret)
	{
		$this->appKey = (string)$appKey;
		$this->appSecret = (string)$appSecret;
		EcommerceHelper::setAppKeyAndSecret($this->getAppKey(), $this->getAppSecret());
	}

	private function getAppKey() {
		return $this->appKey;
	}

	private function getAppSecret() {
		return $this->appSecret;
	}

	/**
	 * GET请求
	 * @param $api
	 * @param $version
	 * @param array $params
	 * @return mixed
	 */
	public function get($api, $version, $params = [])
	{
		return $this->doGet($api, $version, $params);
	}

	/**
	 * 获取接口请求URL
	 * @param $api
	 * @param $version
	 * @param array $params
	 * @return mixed
	 */
	public function getUrl($api, $version, $params = [])
	{
		HttpHelper::setConfig(['API_VERSION' => !empty($version) ? $version : '']);
		$url = HttpHelper::buildRequestUrl($api);
		return HttpHelper::getUrl($url, $params);
	}

	/**
	 * post请求
	 * @param $api
	 * @param $version
	 * @param array $files
	 * @param array $params
	 * @return mixed
	 */
	public function post($api, $version, $files = [], $params = [])
	{
		return $this->doPost($api, $version, $params);
	}

	/**
	 * GET请求
	 * @param $api
	 * @param $version
	 * @param array $params
	 * @param array $config
	 * @return mixed
	 */
	private function doGet($api, $version, $params = [], $config = [])
	{
		HttpHelper::setConfig(['API_VERSION' => !empty($version) ? $version : '']);
		$url = HttpHelper::buildRequestUrl($api);
		return $this->parseResponse(HttpHelper::get($url, $params));
	}

	/**
	 * POST请求
	 * @param $api
	 * @param $version
	 * @param array $params
	 * @param array $files
	 * @param array $config
	 * @return mixed
	 */
	private function doPost($api, $version, $params = [], $files = [], $config = [])
	{
		HttpHelper::setConfig(['API_VERSION' => !empty($version) ? $version : '']);
		$url = HttpHelper::buildRequestUrl($api, $files);
		return $this->parseResponse(HttpHelper::post($url, $params, $files));
	}


	/**
	 * json decode
	 * @param $responseData
	 * @return mixed
	 */
	private function parseResponse($responseData)
	{
		return json_decode($responseData, true);
	}
}