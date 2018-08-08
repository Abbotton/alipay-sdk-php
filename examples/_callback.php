<?php

/**
 * @var Alipay\AopClient $aop
 */
$aop = require __DIR__ . '/_bootstrap.php';

ob_start(); // 启动输出缓冲区

// ---业务代码开始---

if (php_sapi_name() === 'cli') {
    // 若为控制台环境运行此脚本，则使用输入参数解析为数组作为参数
    parse_str($argv[1], $_POST);
}

try {
    $passed = $aop->verify(); // 验证支付宝服务器发来的参数数据签名
} catch (\Exception $ex) {
    $passed = null;
    printf('%s | %s' . PHP_EOL, get_class($ex), $ex->getMessage()); // 验证过程发生错误，打印异常信息
}

if ($passed) {
    print_r($params); // 签名验证通过，数据未被篡改且可靠，打印参数
}

// ---业务代码结束---

$file = fopen('logs/callback.log', 'a'); // 追加模式打开日志文件
fwrite($file, date('Y-m-d H:i:s') . PHP_EOL); // 写入当前时间

$content = ob_get_clean(); // 获取缓冲区数据，并丢弃
fwrite($file, $content . PHP_EOL); // 写入缓冲区数据
fclose($file); // 关闭文件

echo 'success'; // 输出 `success`，否则支付宝服务器将会重复通知
