<?php

/**
 * @var Alipay\AopClient $aop
 */
$aop = require __DIR__ . '/_bootstrap.php';

$file = fopen('logs/callback.log', 'a'); // 追加模式打开日志文件
fwrite($file, date('Y-m-d H:i:s') . PHP_EOL); // 写入当前时间

// 拦截输出缓冲区，以便于把输出记录到文件
ob_start(function ($buffer, $phase) use ($file) {
    fwrite($file, $buffer);
    return $buffer;
});

// 判断是否为控制台环境运行此脚本
if (php_sapi_name() === 'cli') {
    parse_str($argv[1], $params); // 使用输入参数解析为数组后，作为参数
} else {
    $params = $_POST; // 直接使用 $_POST 作为参数
}

// ---业务代码开始---
try {
    // 获取签名器，使用 `verifyByParams` 验证签名
    $aop->getSigner()->verifyByParams(
        $params, // 支付宝服务器发来的参数数组
        $aop->getKeyPair()->getPublicKey() // 获取支付宝公钥，用于验证签名
    );
    print_r($params); // 验证签名成功，数据未被篡改且可靠，打印参数
} catch (\Exception $ex) {
    // 验证签名失败或发生错误，打印异常信息
    printf('%s | %s' . PHP_EOL, get_class($ex), $ex->getMessage());
}
// ---业务代码结束---

// 刷新缓冲区（此处会触发前面设置的回调函数）
ob_flush();
// 文件追加换行
fwrite($file, PHP_EOL);
// 关闭文件
fclose($file);
