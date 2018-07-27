<img src="https://i.loli.net/2018/07/24/5b56e980b155e.png" width="40px" height="40px"> Alipay SDK for PHP
==========

支付宝开放平台（又称：蚂蚁金服开放平台）**第三方** PHP SDK，基于官方 3.3.0 版本。

[![Build Status](https://travis-ci.org/wi1dcard/alipay-sdk-php.svg?branch=master)](https://travis-ci.org/wi1dcard/alipay-sdk-php)
[![Coverage Status](https://coveralls.io/repos/github/wi1dcard/alipay-sdk-php/badge.svg?branch=master)](https://coveralls.io/github/wi1dcard/alipay-sdk-php?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wi1dcard/alipay-sdk-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wi1dcard/alipay-sdk-php/?branch=master)
[![StyleCI](https://github.styleci.io/repos/141678964/shield?branch=master)](https://github.styleci.io/repos/141678964)

## 主要目的

- [x] 集成 Composer。
- [x] 降低 PHP 依赖至 5.4。
- [x] 移除官方 SDK 内 [`lotusphp`](https://github.com/qinjx/lotusphp) 依赖。
- [x] 整理代码风格使其符合 `PSR-1`、`PSR-2`。
- [x] 增加单元测试。
- [x] 兼容 PHP 7.2，<del>替换 MCrypt 为 OpenSSL</del>。
- [x] 移除官方 API 文档内 `已弃用` 特性。
- [x] 移除难以拓展的调试、日志等特性，以便于集成第三方框架和扩展包。
- [x] 移除编码转换特性，统一使用 `UTF-8`。
- [ ] 其它优化，持续进行中……

## 如何使用

除非你已经通读官方入门文档，否则请先阅读 [快速开始](QUICKSTART.md) 部分。

1. Composer 安装。

    ```bash
    composer require "wi1dcard/alipay-sdk:^0.1" --prefer-dist
    ```

2. 创建 `AlipaySigner` 实例。

    ```php
    $signer = \Alipay\AlipaySigner::create(
        '应用私钥',
        '支付宝公钥',
    );
    ```

3. 创建 `AopClient` 实例。

    ```php
    $aop = new AopClient('APP_ID', $signer);
    ```

    此对象通常情况下需要贯穿整条业务，除非你需要在同一套代码内处理多个商户号/小程序，否则只需要在初始化阶段创建一次即可。

4. 创建 `AlipayRequest` 实例。

    ```php
    $request = \Alipay\AlipayRequestFactory::createByApi('sample.api.name', [
        'request-param-key' => 'request-param-value',
        'foo' => 'bar',
        // ...
    ]);
    ```

5. 发送请求。

    ```php    
    $result = $aop->execute($request)->getData();
    ```

    所有错误（包括但不限于网络通信异常、数据格式异常、支付宝服务器返回的错误）都会被转换为异常，请注意捕捉。

6. 查看实例，请移步 [`examples`](examples/) 目录。

## 注意

- 请不要依赖任何在官方 SDK 内被标注为 `private` 的属性，它们可能会在迭代中被修改或废弃。
- 请不要依赖任何在官方 API 文档内被标注为 `已废弃` 的特性，它们将会在迭代中被废弃或移除。
- 本 SDK 只适用于目前正在开发或即将开始开发的项目；由于将会采取相对激进的态度开发，所以请勿尝试将原有代码迁移至本 SDK。
- 本 SDK 已移除所有编码转换特性；请确保执行上传文件请求时，文件编码为 `UTF-8` 而非 `GBK`。

## 实用工具

可执行文件位于 [`bin`](bin/) 目录下，点此查看[详细说明](bin/README.md)。

## 其它资源

官方：

- [支付宝开放平台 - API 文档](https://docs.open.alipay.com/api/)
- [支付宝开放平台 - 开发者社区](https://openclub.alipay.com/index.php?c=thread&a=subforum&fid=66)
- [支付宝小程序 - 开发文档](https://docs.alipay.com/mini/introduce)

非官方：

- [支付宝小程序调用 wxParse 解析](https://openclub.alipay.com/read.php?tid=3830&fid=66)
- 微信小程序转支付宝小程序开源工具（未测试）：
    - <https://github.com/foxitdog/wx2ali>
    - <https://github.com/aOrz/wxmp2antmp>
    - <https://github.com/douzi8/wxToAlipay>

## 感谢

- [支付宝开放平台 SDK](https://docs.open.alipay.com/54/103419/)

## 感想

最后，一点感想。

作为一个名不见经传的小白，不敢妄言阿里的工程师技术欠佳；但可以确定的是，官方提供的 PHP SDK 绝对不是用心之作。

做开放平台，对待第三方开发者是这样的态度，怎能做到与微信比肩？

硬广，欢迎关注我们的产品：

[<img src="https://i.loli.net/2018/07/24/5b56dda76b2ba.png" width="30%" height="30%">](http://www.zjhejiang.com/)