# Alipay SDK for PHP

支付宝非官方 PHP SDK（基于官方 3.3.0 版本），开发中，敬请关注。

## 主要目的

- [x] 集成 Composer。
- [x] 降低 PHP 依赖至 5.4。
- [x] 移除官方代码内 [`lotusphp`](https://github.com/qinjx/lotusphp) 依赖。
- [x] 整理代码风格使其符合 `PSR-1`、`PSR-2`。
- [ ] 增加单元测试。
- [ ] 兼容 PHP 7.2，<del>替换 MCrypt 为 OpenSSL</del>。
- [ ] 移除官方 API 文档内弃用的特性。
- [ ] 移除内部封装的调试、日志等特性，以便于集成第三方框架和扩展包。
- [ ] 其它优化，持续进行中……

## 如何使用

截至目前，本仓库 **暂未改动** 任何官方 SDK 内暴露的 `类名` / `方法名` / `参数名` 以及 `参数顺序`，使用只需调整命名空间即可。

- Composer 安装。

    ```bash
    composer require wi1dcard/alipay-sdk dev-master
    ```

- 命名空间对应如下。

    - 全局：`Alipay\`
    - 请求：`Alipay\Request`
    - 异常：`Alipay\Exception`
    - 辅助函数：`Alipay\Helper`

API、实例以及其它资源，请参见[官方文档](#其它资料)。

## 注意

- 请不要依赖任何在官方 SDK 内被标注为 `private` 的属性，它们可能会在迭代中被修改或废弃。
- 请不要依赖任何在官方 API 文档内被标注为 `已废弃` 的特性，它们将会在迭代中被废弃或移除。
- 本 SDK 只适用于目前正在开发或即将开始开发的项目；由于将会采取相对激进的态度进行精简或移除，所以请 **不要** 尝试将原有代码迁移至本 SDK。

## 其它资料

- [支付宝小程序文档](https://docs.alipay.com/mini/introduce)
- [支付宝官方 API 文档](https://docs.open.alipay.com/api/)

## 感谢

- [支付宝官方 SDK](https://docs.open.alipay.com/54/103419/)