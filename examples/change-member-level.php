<?php
/**
 * 会员等级接口
 */
require_once './vendor/autoload.php';

// 未使用 composer 进行包管理的引入方式
// require_once '/path/to/tongduiba-open-sdk/open-sdk/vendor/autoload.php';

$appKey = '';//必填, 应用appKey, 应用后台获取该值
$appSecret = '';//必填, 应用appSecret, 应用后台获取该值

$client = new \Tongduiba\Open\Client($appKey, $appSecret);
$method = '/sdk/api/change-member-level';
$apiVersion = '0.1.0';

/**
 * 更改用户会员等级
 *
 * @param string $unionId 用户唯一id, 如果该值为空则标识为游客模式
 * @param string $memberLevel 会员等级,例如vip1, 非会员传空字符串""
 * @return {
 *     "code": 0,
 *     "msg": "success",
 *     "data": {
 *         "unionId": "1",
 *         "memberLevel": "vip1"
 *     }
 *}
 */
$params = ['unionId' => '', 'memberLevel' => ''];
$response = $client->get($method, $apiVersion, $params);
var_dump($response);