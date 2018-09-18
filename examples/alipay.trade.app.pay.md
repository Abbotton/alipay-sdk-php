# APP 端唤起支付

阅读本文，你将开始学习如何在支付宝小程序内接入支付。

注：本文所有相对路径，均相对于 `examples` 目录。

## 0x00 准备

阅读 [官方接入文档](https://docs.alipay.com/mini/introduce/tradepay)，申请签约 `APP支付`。

## 0x01 接入

根据文档可知：接入支付，技术层面的流程大致如下。

| 序号       | 内容                                   | 备注                                      | 链接                                                             |
|:-----------|:---------------------------------------|:------------------------------------------|:-----------------------------------------------------------------|
| [1](#step-1) | 小程序端请求我方服务端                 |                                           | [my.httpRequest](https://docs.alipay.com/mini/api/network)       |
| [2](#step-2) | 我方服务端调用 SDK 生成订单字符串      | 此处可以验证用户登录态 / 是否有权限下单等 | [alipay.trade.app.pay](https://docs.open.alipay.com/204/105465/) |
| [3](#step-3) | 小程序端携带订单字符串唤起支付         |                                           | [my.tradePay](https://docs.alipay.com/mini/api/openapi-pay)      |
| [4](#step-4) | 支付宝服务端请求我方服务端通知支付结果 | 我方服务端应当返回 `success`              | [异步通知参数说明](https://docs.open.alipay.com/204/105301/)     |

下面，我们分步骤进行演示。

### STEP-1

在小程序开发者工具内，加入如下代码。

```js
my.httpRequest({
  // 此处需要根据 Web 服务器的配置进行修改
  // 且由于支付宝小程序支付过程不允许在开发者工具调试
  // 所以此 URL 必须确保能被外网访问
  url: 'http://localhost/examples/alipay.trade.app.pay.php',
  dataType: 'text',
  
  success: (res) => {
    console.log(res.data);

    // 请求成功...
  },

  fail: (res) => {
    console.log(res.error, res.errorMessage);
  }
});
```

### STEP-2

本例代码默认无需任何改动。请确保此文件能够被如上 URL 访问。

如需查看源码，请移步：[alipay.trade.app.pay.php](alipay.trade.app.pay.php)。

### STEP-3

在小程序端 `// 请求成功...` 处加入如下代码。

```js
my.tradePay({
  orderStr: res.data,
  success: (res) => {
    // 由于支付宝真机调试日志无法展示对象数据，故转成 JSON 字符串输出
    console.log(JSON.stringify(res));
  },
});
```

### STEP-4

本例代码默认无需任何改动。请确保此文件与 `alipay.trade.app.pay.php` 位于同目录，且均能被外网访问即可。

如需查看源码，请移步：[_callback.php](_callback.php)。

## 0x02 调试

### 监视支付回调

为了展示支付回调效果，`_callback.php` 代码稍显复杂，但同时也为调试提供了方便。

`_callback.php` 将会把数据输出至 `logs/callback.log`；使用如下命令，可以实时监控此文件的追加变动。

> Windows 环境请使用 Git Bash 等，或支付成功后直接查看。

```bash
LOG_FILE=logs/callback.log && touch $LOG_FILE && tail -f $LOG_FILE
```

然而并没有什么输出。正常，我们继续。

### 小程序发起支付

支付宝小程序支付过程不允许在开发者工具调试，所以我们需要使用 `真机调试` 功能。

在小程序开发者工具内，点击左侧边栏第二个按钮（左下有个小齿轮），切换到真机调试页面；输入 APP ID 后，点击 `推送` 即可。

使用支付宝 APP 扫码，跳转到你的小程序。接下来，唤起支付；捐赠你的 0.01 元。

### 最终效果

现在，切换至刚刚的命令行，将会输出如下内容：

```
2018-08-02 11:24:14
Array
(
    [gmt_create] => 2018-08-02 11:24:13
    [charset] => UTF-8
    [seller_email] => 13736456124@163.com
    [subject] => This is a sample subject from wi1dcard/alipay-php-sdk
    [sign] => Il4fdaB8QZCMmaHUmhbLfpGkPuz66rvkVFzVc/42E9W2brNqopN4MtojLyrH2AMi+LSdsPaWVItn6mI1MDjevgvZ0hXInfh3mx/U8P7D6RQVjunIBCMWrWPDuu52AJBPput/tCckijc7O7BdG3Yovf1f1z5MhH3puCvEyS5rvWVPl/EcrhJdcW77ngPVHmGP2LO8WPdO0zgsEOLiy+eiXZXyLmjKqUsRBM5wRlJkJZudW75NeJMkprZvdSQEMT2PHAJkXAgfmw1HEQHbK22NGVDsIjzip74GaHy+EDSZ/Vk8XyNGKNfzI26PZHrvX2783Y2S8sgdzwDF1d9VI+W4Gw==
    [body] => This is a sample body from wi1dcard/alipay-php-sdk
    [buyer_id] => 2088112526392254
    [invoice_amount] => 0.01
    [notify_id] => 7d59ca574563044b0cddc578a913803hxl
    [fund_bill_list] => [{"amount":"0.01","fundChannel":"ALIPAYACCOUNT"}]
    [notify_type] => trade_status_sync
    [trade_status] => TRADE_SUCCESS
    [receipt_amount] => 0.01
    [app_id] => 2018071660720249
    [buyer_pay_amount] => 0.01
    [sign_type] => RSA2
    [seller_id] => 2088131988040170
    [gmt_payment] => 2018-08-02 11:24:14
    [notify_time] => 2018-08-02 11:24:14
    [version] => 1.0
    [out_trade_no] => 144944615dc55547be87acc404b69c739fe1fe9a
    [total_amount] => 0.01
    [trade_no] => 2018080221001004250509421023
    [auth_app_id] => 2018071660720249
    [buyer_logon_id] => wi1***@qq.com
    [point_amount] => 0.00
)
```

而在开发者工具，将会输出如下日志：

```json
{
  "callbackUrl": "",
  "memo": "{}",
  "result": "{\"alipay_trade_app_pay_response\":{\"code\":\"10000\",\"msg\":\"Success\",\"app_id\":\"2018071660720249\",\"auth_app_id\":\"2018071660720249\",\"charset\":\"UTF-8\",\"timestamp\":\"2018-08-02 11:24:14\",\"total_amount\":\"0.01\",\"trade_no\":\"2018080221001004250509421023\",\"seller_id\":\"2088131988040170\",\"out_trade_no\":\"144944615dc55547be87acc404b69c739fe1fe9a\"},\"sign\":\"RKPH8RW+1ZL3kF6h4opzfd1q9RIj/1rRbYAOG/kr2HxjhtDxHDZloJ6ZPsvWHLtZC9CylZ4c7f/z+2l/EhKVhLDQ3YkTdS5fPeaXB7zvV5c40lJ0ou8a5dNKEWFgxEFGnJyjApQ63Uc7+mHgafIc9xmXmn45Ou+3L2hM3qjy7ajBzBqT1cQVG/+NEcPdUNWFNB96XPDTK7xn5CcGQHO4bMpXiuIYTECFIJP1UAqNL/lBIZsEF921CdwUaUZUElgaSN2lYjRemFeofVXWyIRUDPXrRDjb8V+D7EbEV8v3oNRBP8hQSSt5rMqzYClUHxoxJXpeRnvEE1wjDJxIAvmw0A==\",\"sign_type\":\"RSA2\"}",
  "resultCode": "9000"
}
```

其中，`result` 解析后为：

```json
{
  "alipay_trade_app_pay_response": {
    "code": "10000",
    "msg": "Success",
    "app_id": "2018071660720249",
    "auth_app_id": "2018071660720249",
    "charset": "UTF-8",
    "timestamp": "2018-08-02 11:24:14",
    "total_amount": "0.01",
    "trade_no": "2018080221001004250509421023",
    "seller_id": "2088131988040170",
    "out_trade_no": "144944615dc55547be87acc404b69c739fe1fe9a"
  },
  "sign": "RKPH8RW+1ZL3kF6h4opzfd1q9RIj/1rRbYAOG/kr2HxjhtDxHDZloJ6ZPsvWHLtZC9CylZ4c7f/z+2l/EhKVhLDQ3YkTdS5fPeaXB7zvV5c40lJ0ou8a5dNKEWFgxEFGnJyjApQ63Uc7+mHgafIc9xmXmn45Ou+3L2hM3qjy7ajBzBqT1cQVG/+NEcPdUNWFNB96XPDTK7xn5CcGQHO4bMpXiuIYTECFIJP1UAqNL/lBIZsEF921CdwUaUZUElgaSN2lYjRemFeofVXWyIRUDPXrRDjb8V+D7EbEV8v3oNRBP8hQSSt5rMqzYClUHxoxJXpeRnvEE1wjDJxIAvmw0A==",
  "sign_type": "RSA2"
}
```

至此，支付流程接入完毕。

## 0x03 拓展

如果你确实没有外网 IP，没关系。可以使用 <https://webhook.site/> 接收回调通知。使用方法？动手尝试一下吧！