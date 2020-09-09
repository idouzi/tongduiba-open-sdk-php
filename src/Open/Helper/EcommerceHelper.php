<?php

namespace Tongduiba\Open\Helper;

use Tongduiba\Open\Config\EcommerceConfig;

class EcommerceHelper {
	private static $appKey;
	private static $appSecret;

	public static function setAppKeyAndSecret($appKey, $appSecret) {
		self::$appKey = $appKey;
		self::$appSecret = $appSecret;
	}

	protected static function getAppKey() {
		return self::$appKey;
	}

	protected static function getAppSecret() {
		return self::$appSecret;
	}

	/**
	 * 处理业务默认参数
	 * @param $params
	 * @return number
	 */
	public static function setDefaultParams($params) {
		if(!isset($params['s_ver'])) {
			$params['s_ver'] = 1;
		}
		return $params;
	}

	/**
	 * 生成签名后, 将参数组装成url键值对模式
	 *
	 * @param $params array 参数
	 * @return string
	 */
	public static function generateSign($params)
	{
		$stringA = '';
		$params['nonceStr'] = self::generateNonceStr(32);
		$params['timestamp'] = time();
		$params['appKey'] = self::getAppKey();
		// 1:将所有请求参数按照ASCII码从小到大排序（字典序）
		ksort($params);
		foreach ($params as $key => $value) {
			if (is_array($value)) {
				$value = json_encode($value);
			}
			$stringA .= $key . '=' . $value . '&';
		}
		// 2：拼接appSecret参数
		$stringB = $stringA . 'appSecret=' . self::getAppSecret();
		// 3：生成签名
		$params['sign'] = md5($stringB);
		if(isset($params['redirect'])) {
			$params['redirect'] = htmlspecialchars($params['redirect']);
		}
		return http_build_query($params);
	}

	/**
	 * 生成随机字符串
	 *
	 * @param $length int 长度
	 * @return string
	 */
	private static function generateNonceStr($length)
	{
		// 字符集
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$nonceStr = '';
		for ($i = 0; $i < $length; $i++) {
			$index = rand(0, strlen($characters) - 1);
			$nonceStr .= $characters[$index];
		}
		return $nonceStr;
	}

	private static function isUseProxy()
	{
		return (bool)self::getEnvVal(EcommerceConfig::ENV_PROXY_ENABLE);
	}

	public static function buildUrlAndHeaders($url, $getParams = [])
	{
		$ret = [
			'url' => $url,
			'headers' => [],
		];
		if (strpos($url, 'http') !== 0) {
			$url = EcommerceConfig::ENV_API_PROTOCOL . '://' . $url;
		}
		$urlArr = parse_url($url);
		$ret['url'] = self::buildUrl($urlArr, $getParams);

		if (!self::isUseProxy()) {
			return $ret;
		}
		$ret['headers'] = self::getHttpHeaders($urlArr['host']);
		return $ret;
	}

	private static function buildUrl($urlArr, $getParams = [])
	{
		$url = $urlArr['scheme'] . '://' . $urlArr['host'];
		$url .= isset($urlArr['path']) ? $urlArr['path'] : '';
		$queryArr = isset($urlArr['query']) ? parse_str($urlArr['query']) : [];
		$urlArr['query'] = self::generateSign(array_merge($queryArr, $getParams));
		$url .= isset($urlArr['query']) ? ('?' . $urlArr['query']) : '';
		return $url;
	}

	private static function getHttpHeaders($hostname)
	{
		return [
			'Host' => $hostname,
			'Protocol' => EcommerceConfig::ENV_API_PROTOCOL,
			'Tdb-token' => self::getEnvVal(EcommerceConfig::ENV_PROXY_TOKEN),
			'Tdb-Keepalive-Timeout' => self::getEnvVal(EcommerceConfig::ENV_PROXY_KEEPALIVE_TIMEOUT),
			'Tdb-Keepalive-Poolsize' => self::getEnvVal(EcommerceConfig::ENV_PROXY_KEEPALIVE_POOL_SIZE),
		];
	}

	private static function getEnvVal($key)
	{
		if (empty($key)) {
			return null;
		}
		if (isset($_SERVER[$key])) {
			return $_SERVER[$key];
		}
		return null;
	}
}