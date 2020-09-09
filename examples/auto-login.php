<?php
/**
 * 免登录地址接口
 */
require_once './vendor/autoload.php';

// 未使用 composer 进行包管理的引入方式
// require_once '/path/to/tongduiba-open-sdk/open-sdk/vendor/autoload.php';

$appKey = '';//必填, 应用appKey, 应用后台获取该值
$appSecret = '';//必填, 应用appSecret, 应用后台获取该值

$client = new \Tongduiba\Open\Client($appKey, $appSecret);
$method = '/user/login/auto-login';
$apiVersion = '0.1.0';

/**
 * 生成自动登录url链接
 *
 * @param string $unionId 用户唯一id, 如果该值为空则标识为游客模式
 * @param string $redirect 登录后重定向地址, 如果该值为空则重定向到商城首页
 * @return string 自动登录的url链接
 */
$params = ['unionId' => '', 'redirect' => ''];
$response = $client->getUrl($method, $apiVersion, $params);
var_dump($response);