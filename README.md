<img src="https://i.loli.net/2018/07/24/5b56e980b155e.png" width="40px" height="40px"> Alipay SDK for PHP
==========

🐜 支付宝开放平台（又称：蚂蚁金服开放平台）**第三方** PHP SDK，基于[官方][OfficialSDK]最新版本。

* **目录**
  * [为什么不用官方](#为什么不用官方)
  * [主要目的](#主要目的)
  * [小试牛刀](#小试牛刀)
  * [如何使用](#如何使用)
  * [注意事项](#注意事项)
  * [实用工具](#实用工具)
  * [其它资源](#其它资源)
  * [已知 Issue](#已知-issue)
  * [感谢](#感谢)

## 为什么不用官方

| 官方 SDK                                                 | 本 SDK                                                    | 链接                                                                                                               |
| :------------------------------------------------------- | :-------------------------------------------------------- | :----------------------------------------------------------------------------------------------------------------- |
| 无 Composer 集成，自动加载依赖第三方 PHP 框架 `lotusphp` | 集成                                                      | [![Packagist](https://img.shields.io/packagist/v/abbotton/alipay-sdk.svg)][Packagist]                              |
| 代码不严谨，各种 Warning                                 | 持续构建，Notice 也不放过                                 |  |
| 零单元测试                                               | 99% 测试覆盖率                                            |  |
| 代码风格、命名风格鱼龙混杂                               | PSR1 + PSR2                                               |  |
| 几乎零 PHPDoc                                            | 持续补充中                                                | [对比图](https://i.loli.net/2018/08/01/5b611dc917bea.png)                                                          |
| 异常和错误处理不统一                                     | 所有错误都将以异常的形式抛出，确保返回数据可靠            | [Exceptions](aop/Exception)                                                                                        |
| 需手动根据接口名拼接请求类名                             | 请求类工厂，根据 API 名直接创建请求类                     | [AlipayRequestFactory](aop/AlipayRequestFactory.php)                                                               |
| 耦合度高，难以升级或替换                                 | 几乎完全解耦，任意替换签名 / 密钥 / 响应 甚至 HTTP 客户端 | [AopClient](aop/AopClient.php)                                                                                     |

[Packagist]: https://packagist.org/packages/abbotton/alipay-sdk

## 主要目的

- [x] 集成 Composer。
- [x] 降低 PHP 依赖至 5.4。
- [x] 整理代码风格使其符合 `PSR-1`、`PSR-2`。
- [x] 增加单元测试。
- [x] 兼容 PHP 7.x。
- [x] 移除官方 API 文档内 `已弃用` 特性。
- [x] 移除难以拓展的调试、日志等特性，以便于集成第三方框架和扩展包。
- [x] 移除编码转换特性，统一使用 `UTF-8`。
- [ ] 其它优化，持续进行中 ...

## 小试牛刀

- [获取小程序用户信息](examples/alipay.system.oauth.token.md)

## 如何使用

1. Composer 安装。

    ```bash
    composer require abbotton/alipay-sdk
    ```

2. 创建 `AlipayKeyPair` 实例。

    ```php
   // 通过密钥文件创建(推荐)
    $keyPair = \Alipay\Key\AlipayKeyPair::create(
        __DIR__ . '/private.pem',
        __DIR__ . '/public.pem'
    );
   
   // 通过密钥字符串创建
   $privateKey = '-----BEGIN RSA PRIVATE KEY-----
   // ......
   kXTZhB4nQqZXWFu8R+RdqC6gTh9k2dDz0NlibYSW9xApWRN572M9/n737110Yxa2
   JZX27eBHLqR8aCZcXkADRpe9+yAL9SqErEOMh26nWFtwP5ZugHRf
    -----END RSA PRIVATE KEY-----';
   $publicKey = '-----BEGIN PUBLIC KEY-----
   // ......
    C9ykBV6BfujeeVQ2wUdpxFtI4gW8A4rgqecMvbL/KngU+aChG+W6SGXD8QY0fC4d
    4QIDAQAB
    -----END PUBLIC KEY-----';
   $keyPair = \Alipay\Key\AlipayKeyPair::create($privateKey, $publicKey);
    ```

    `AlipayKeyPair` 用于存储应用私钥、支付宝公钥；两份密钥将分别用于与支付宝服务器通信时，生成请求签名、验证响应签名等。

3. 创建 `AopClient` 实例。

    ```php
    $aop = new \Alipay\AopClient('APP_ID', $keyPair);
    ```

    `AopClient` 通常情况会贯穿整条业务，除非你须要在同一套代码内处理多个商户号/小程序，否则只需在初始化阶段创建一次即可。

4. 根据业务需要，创建 `AlipayRequest` 实例。

    ```php
   $bizContent = [
        'start_time' => '2021-08-01 00:00:00',
        'end_time' => '2021-08-31 23:59:59',
        'type' => 'TRANSFER',
        'page_no' => 1
   ];
   
   // 通过工厂类创建
    $request = (new \Alipay\AlipayRequestFactory)->create('alipay.data.bill.transfer.query', [
        'biz_content' => $bizContent,
        // ...
    ]);
   
   // 直接创建
   $request = (new \Alipay\Request\AlipayRequest())
        ->setApiMethodName('alipay.data.bill.transfer.query')
        ->setBizContent($bizContent);
    ```

5. 发送请求，获得响应数据。

    ```php
    $result = $aop->execute($request)->getData();
    ```

    所有错误（包括但不限于网络通信异常、数据格式异常、支付宝服务器返回的错误）都会被转换为异常，请注意捕捉。

6. 更多实例，请移步 [`examples`](examples/) 目录。

    最后，官方 SDK 内 `AopClient::pageExecute()` 被分离为 `pageExecuteUrl` 和 `pageExecuteForm`。
    `AopClient::sdkExecute()` 和 `AopClient::execute()` 方法名保持不变，参数和返回值有所改动。

## 注意事项

- 请不要依赖任何在官方 SDK 内被标注为 `private` 的属性，它们可能已在迭代中被修改或废弃。
- 请不要依赖任何在官方 API 文档内被标注为 `已废弃` 的特性，它们可能已在迭代中被废弃或移除。
- 本 SDK 已移除所有编码转换特性；请确保执行上传文件请求时，文件编码为 `UTF-8` 而非 `GBK`。

## 实用工具

可执行文件位于 [`bin`](bin/) 目录下，点此查看 [详细说明](bin/README.md)。

## 其它资源

- [支付宝开放平台 - API 文档](https://docs.open.alipay.com/api/)
- [支付宝开放平台 - 开发者社区](https://openclub.alipay.com/index.php)

## 已知 Issue

OpenSSL 在 Win32 平台需要配置 `openssl.cnf` 路径，参见 [OpenSSL 安装 - PHP 手册](http://php.net/manual/zh/openssl.installation.php)。

在本 SDK 内，也可通过自定义 `$configargs` 参数来自定义此文件路径，而不需要配置环境变量；参见 [examples/keys/generate.php](examples/keys/generate.php)。

目前已知以下方法依赖于此配置文件：

- 生成密钥对：`AlipayKeyPair::generate()`
- 将私钥资源转换为字符串：`AlipayPrivateKey::toString()`

## 感谢

- [支付宝开放平台 SDK][OfficialSDK]
- [第一作者Wi1dcard](https://github.com/wi1dcard)

[OfficialSDK]: https://docs.open.alipay.com/54/103419/