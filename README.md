# Alipay SDK for PHP

支付宝非官方 PHP SDK（基于官方 3.3.0 版本），开发中，敬请关注。

## 主要目的

- [x] 集成 Composer。
- [ ] 降低 PHP 依赖至 5.4。
- [x] 移除官方 SDK 内 [`lotusphp`](https://github.com/qinjx/lotusphp) 依赖。
- [x] 整理代码风格使其符合 `PSR-1`、`PSR-2`。
- [ ] 增加单元测试。
- [ ] 兼容 PHP 7.2，<del>替换 MCrypt 为 OpenSSL</del>。
- [x] 移除官方 API 文档内 `已弃用` 特性。
- [x] 移除难以拓展的调试、日志等特性，以便于集成第三方框架和扩展包。
- [x] 移除编码转换特性，统一使用 `UTF-8`。
- [ ] 其它优化，持续进行中……

## 如何使用

1. Composer 安装。

    ```bash
    composer require wi1dcard/alipay-sdk dev-master
    ```

2. 创建 `AopClient` 实例。

    ```php
    $aop = \Alipay\AopClient::create(
        'App Id',
        'Sign Type',
        'App Private Key',
        'Alipay Public Key'
    );
    ```

3. 创建 `AlipayRequest` 实例。

    ```php
    $request = \Alipay\AlipayRequestFactory::createByApi('API Name');
    ```

4. 发送请求。

    ```php
    $result = $aop->execute($request);
    ```

5. 查看实例，请移步 `examples` 目录。

## 注意

- 请不要依赖任何在官方 SDK 内被标注为 `private` 的属性，它们可能会在迭代中被修改或废弃。
- 请不要依赖任何在官方 API 文档内被标注为 `已废弃` 的特性，它们将会在迭代中被废弃或移除。
- 本 SDK 只适用于目前正在开发或即将开始开发的项目；由于将会采取相对激进的态度进行精简或移除，所以请 **不要** 尝试将原有代码迁移至本 SDK。
- 由于本 SDK 已移除所有编码转换特性，所以请确保进行上传文件请求时，文件是 UTF-8 编码，而非 GBK。

## 实用工具

可执行文件全部位于 `bin` 目录下，点此查看[详细说明](bin/README.md)。

## 其它资源

- [支付宝开放平台 API 文档](https://docs.open.alipay.com/api/)
- [支付宝小程序文档](https://docs.alipay.com/mini/introduce)
- [支付宝小程序开发者社区](https://openclub.alipay.com/index.php?c=thread&a=subforum&fid=66)
- [支付宝小程序内利用 wxParse](https://openclub.alipay.com/read.php?tid=3830&fid=66)
- 三款微信小程序转支付宝小程序开源工具（未测试）
    - <https://github.com/foxitdog/wx2ali>
    - <https://github.com/aOrz/wxmp2antmp>
    - <https://github.com/douzi8/wxToAlipay>


## 感谢

- [支付宝官方 SDK](https://docs.open.alipay.com/54/103419/)