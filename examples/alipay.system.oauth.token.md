# 获取小程序用户信息

阅读本文，你将开始学习如何调通 `支付宝小程序` - `PHP SDK`，并实现 [获取会员信息](https://docs.alipay.com/mini/introduce/auth)。

注：本文所有相对路径，均相对于 `examples` 目录。

## 0x00 准备

在本小节，你需要完成以下目标：

- 在你的电脑安装 `小程序开发者工具`。
- 拿到小程序的 `APP ID`。
- 克隆本仓库。

小程序开发者工具下载地址：<https://docs.alipay.com/mini/ide/download>。

在等待下载的过程中，你可以：

- [入驻开发者](https://docs.alipay.com/mini/introduce/register)
- [创建小程序](https://docs.alipay.com/mini/introduce/create)

创建完成后，在 [这里](https://open.alipay.com/platform/miniIndex.htm#/) 查看你的 `APP ID`。

另外，使用 `git clone` 命令克隆本仓库即可，并确保 `examples` 目录下的 PHP 文件能够在浏览器中访问。

## 0x01 小程序

在本小节，你需要完成以下目标：

- 创建示例项目，并配置 APP ID。

打开 `小程序开发者工具`，使用手机支付宝扫码登录。

点击 `新建`，点击 `工程示例`，输入路径并创建。

进入主界面后，点击下方 `调试器`，即可打开开发者工具；点击左侧边栏下方的 `☁️` 按钮，输入 APP ID。

更多说明，请查看 [官方文档](https://docs.alipay.com/mini/ide/overview)。

## 0x02 密钥

在本小节，你需要完成以下目标：

- 生成应用公钥、私钥。
- 上传应用公钥。
- 获得支付宝公钥。

如果你习惯用 GUI，请移步：<https://docs.alipay.com/mini/introduce/rsa2>。

如果你习惯用 Shell，请继续往下看。

```bash
../bin/genrsa
```

首先执行此命令，生成 `app_private_key.pem` 和 `app_public_key.pem`，如同 `test` 目录下的一样。同时会输出一段 base64 编码后的签名字符串，我们留作备用。

打开 `小程序详情页 - 设置`，点击 `设置应用公钥`。

使用文本编辑器复制 `app_public_key.pem` 文件内容，掐头去尾（不包含 `-----BEGIN RSA PRIVATE KEY-----` 和 `-----END RSA PRIVATE KEY-----`）粘贴至文本框内。

点击 `验证公钥正确性`，输入此前的「签名字符串」，应当验证成功。

点击 `查看支付宝公钥`，复制公钥字符串，执行：

```bash
../bin/wrap-key '你的支付宝公钥' > alipay_public_key.pem
```

即可生成 `alipay_public_key.pem` 文件。

准备好 `alipay_public_key.pem` 和 `app_private_key.pem`，进入下一步。

## 0x03 PHP SDK

在本小节，你需要完成以下目标：

- 配置示例环境变量。
- 执行示例。
- 获取用户信息。

编辑 [`.env`](.env) 文件，配置你的环境变量。

打开 `小程序开发者工具`，修改 `app.js` 的主要代码：

```javascript
my.getAuthCode({
  scopes: 'auth_user',
  success: (res) => {
    if (res.authCode) {
      my.httpRequest({
        // 此处需要根据本地 Web 服务器的配置修改，假设 http://localhost/ 映射到本仓库根目录则无需改动
        url: 'http://localhost/examples/alipay.system.oauth.token.php',
        data: {
          authcode: res.authCode,
        }
      });
    }
  },
});
```

打开 `小程序开发者工具` - `调试器` - `Network`，点击右上角的 `刷新` 图标。

在请求列表内找到 `alipay.system.oauth.token.php`，若响应类似 [文档内的格式](https://docs.open.alipay.com/api_9/alipay.system.oauth.token#s5) 说明你已经正常调通接口。例如：

```
Array
(
    [access_token] => authusrB01c97b02******d8baed95df89af4X25
    [alipay_user_id] => 208800528300******2426192511725
    [expires_in] => 1296000
    [re_expires_in] => 2592000
    [refresh_token] => authusrB67779be5******94c9a20909bd8A25
    [user_id] => 2088112****92254
)
```

最后，你可以执行：

```bash
php alipay.user.info.share.php <access_token>
```

将会得到如下所示用户详细信息：

```
Array
(
    [code] => 10000
    [msg] => Success
    [avatar] => https://tfs.alipayobjects.com/images/partner/*****
    [city] => 青岛市
    [gender] => m
    [is_certified] => T
    [is_student_certified] => F
    [nick_name] => ****
    [province] => 山东省
    [user_id] => 2088112****92254
    [user_status] => T
    [user_type] => 2
)
```

至此，用户授权流程完毕。

## 0x04 接下来...

你可以：

- [在你的项目中安装本扩展包](../README.md#如何使用)
- [阅读本例代码](alipay.system.oauth.token.php)