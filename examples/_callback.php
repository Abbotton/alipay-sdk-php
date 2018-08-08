<?php

/**
 * @var Alipay\AopClient $aop
 */
$aop = require __DIR__ . '/_bootstrap.php';

ob_start(); // 启动输出缓冲区

// ---业务代码开始---
try {
    // 判断是否为控制台环境运行此脚本
    if (php_sapi_name() === 'cli') {
        parse_str($argv[1], $params); // 使用输入参数解析为数组作为参数
        $aop->verify($params);
    } else {
        $aop->verify(); // 直接使用 $_POST 作为参数
    }
    print_r($params); // 验证签名成功，数据未被篡改且可靠，打印参数
} catch (\Exception $ex) {
    // 验证签名失败或发生错误，打印异常信息
    printf('%s | %s' . PHP_EOL, get_class($ex), $ex->getMessage());
}
// ---业务代码结束---

$file = fopen('logs/callback.log', 'a'); // 追加模式打开日志文件
fwrite($file, date('Y-m-d H:i:s') . PHP_EOL); // 写入当前时间

$content = ob_get_clean(); // 获取缓冲区数据，并丢弃
fwrite($file, $content . PHP_EOL); // 写入缓冲区数据
fclose($file); // 关闭文件

echo 'success'; // 输出 `success`，否则支付宝服务器将会重复通知