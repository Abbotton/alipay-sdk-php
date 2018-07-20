# Alipay SDK for PHP

支付宝非官方 PHP SDK（基于官方 3.3.0 版本）。

## 主要目的

- [x] 集成 Composer。
- [x] 降低 PHP 依赖至 5.4。
- [x] 移除官方代码内 [`lotusphp`](https://github.com/qinjx/lotusphp) 依赖。
- [x] 整理代码风格使其符合 `PSR-1`、`PSR-2`。
- [ ] 增加单元测试。
- [ ] 兼容 PHP 7.2，替换 MCrypt 为 OpenSSL。
- [ ] 其它优化。

## 如何使用

截至目前，本仓库 **暂未改动** 任何官方 SDK 内的 `类名` / `函数名` / `方法名` / `参数名` / `参数顺序`，使用只需调整命名空间即可。

**注意**：请不要依赖任何在官方 SDK 内被标注为 `private` 的属性，它们可能会在迭代中被修改或废弃。

- Composer 安装。

    ```bash
    composer require wi1dcard/alipay-sdk dev-master
    ```

- 命名空间对应如下。

    - 全局：`Alipay\`
    - 请求：`Alipay\Request`

API、实例以及其它资源，请参见[官方文档](https://docs.open.alipay.com/54/cyz7do/)。

## 感谢

- [支付宝官方 SDK](https://docs.open.alipay.com/54/103419/)