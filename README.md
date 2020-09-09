# tongduiba-open-sdk-php
通兑吧 SDK for PHP

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]


## NOTICE

请确保服务器端php版本 >= 5.6.0, 建议 >= 7.0, 且php开启ssl支持; 且商城后台正确获取到appKey,appSecret参数
已开放授权调用的API文档，请查看(http://m.tongdui8.com/docs/)


## 安装

1. 使用 `Composer`
推荐使用该方式安装, 更优雅  

``` bash
$ composer require tongduiba/open-sdk
```

2. 不适应 `Composer` 管理  

如果你的项目不使用`Composer`管理, 可以直接下载[Release包](https://github.com/idouzi/tongduiba-open-sdk-php/releases) 并解压, 然后在项目中添加如下代码:  
请注意, 需要下载的是最新的 `tongduiba-open-sdk.zip` 压缩包, 而不是 `Source code`  压缩包.  
`/path/to/` 更改为项目实际路径.   
``` php
require_once '/YOUR_SDK_PATH/tongduiba-open-sdk-php/open-sdk/vendor/autoload.php';
``` 

## 使用

详情参考[examples](examples) 更多API文档请查看(http://m.tongdui8.com/docs/)

### 1. 生成自动登录url链接

#### 生成自动登录url链接
``` php
require_once './vendor/autoload.php';

$appKey = 'YOUR_APP_KEY';
$appSecret = 'YOUR_APP_SECRET';

$client = new \Tongduiba\Open\Client($appKey, $appSecret);
$method = '/user/login/auto-login';
$apiVersion = '0.1.0';

$params = ['unionId' => 'Your APP User’s UnionId', 'redirect' => ''];
$response = $client->getUrl($method, $apiVersion, $params);
var_dump($response);
```

### 2. 更改用户会员等级

#### 更改用户会员等级
``` php
require_once './vendor/autoload.php';

$appKey = 'YOUR_APP_KEY';
$appSecret = 'YOUR_APP_SECRET';
$apiVersion = '0.1.0';

$params = ['unionId' => 'Your APP User’s UnionId', 'memberLevel' => 'To Change Member’s Level'];
$response = $client->get($method, $apiVersion, $params);
var_dump($response);
```

## Security

If you discover any security related issues, please using the issue tracker.


## License

The MIT License. Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/tongduiba/open-sdk.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/tongduiba/open-sdk.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/tongduiba/open-sdk
[link-downloads]: https://packagist.org/packages/tongduiba/open-sdk
