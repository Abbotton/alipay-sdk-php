<?php

use Alipay\AlipayRequestFactory;

$aop = require __DIR__ . '/_bootstrap.php';

/**
 * 根据文件名剥离请求名
 */
$apiName = basename(__FILE__, '.php');

/**
 * 拼接回调通知 URL（即本目录下 `_callback.php` 文件）
 */

if (php_sapi_name() === 'cli') {
    $callbackUrl = '';
} else {
    $hostInfo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
    $callbackUrl = $hostInfo . dirname($_SERVER['DOCUMENT_URI']) . '/_callback.php';
}

/**
 * 构建请求类，此处只演示必填参数
 */
$request = AlipayRequestFactory::create($apiName, [
    'notify_url' => $callbackUrl,
    'biz_content' => [
        'body' => 'This is a sample body from wi1dcard/alipay-php-sdk', // 对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加
        'subject' => 'This is a sample subject from wi1dcard/alipay-php-sdk', // 商品的标题 / 交易标题 / 订单标题 / 订单关键字等
        'out_trade_no' => sha1(microtime() . uniqid()), // 商户网站唯一订单号
        'total_amount' => '0.01', // 订单总金额，单位为元，精确到小数点后两位，取值范围 [0.01,100000000]
        'buyer_id' => '2088702383822474',
    ],
]);

/**
 * 构建请求字符串并输出
 */
try {
    $data = $aop->execute($request)->getData();
    print_r($data);
} catch (\Exception $ex) {
    http_response_code(500);
    print_r($ex);
}
