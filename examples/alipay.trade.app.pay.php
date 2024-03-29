<?php

use Alipay\AlipayRequestFactory;

$aop = require __DIR__.'/_bootstrap.php';

/**
 * 根据文件名剥离请求名.
 */
$apiName = basename(__FILE__, '.php');

/**
 * 拼接回调通知 URL（即本目录下 `_callback.php` 文件）.
 */
$hostInfo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'];
$callbackUrl = $hostInfo.dirname($_SERVER['DOCUMENT_URI']).'/_callback.php';

/**
 * 构建请求类，此处只演示必填参数.
 */
$request = AlipayRequestFactory::create($apiName, [
    'notify_url'  => $callbackUrl,
    'biz_content' => [
        'body'         => 'This is a sample body from abbotton/alipay-php-sdk', // 对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加
        'subject'      => 'This is a sample subject from abbotton/alipay-php-sdk', // 商品的标题 / 交易标题 / 订单标题 / 订单关键字等
        'out_trade_no' => sha1(microtime().uniqid()), // 商户网站唯一订单号
        'total_amount' => '0.01', // 订单总金额，单位为元，精确到小数点后两位，取值范围 [0.01,100000000]
        'product_code' => 'QUICK_MSECURITY_PAY', // 销售产品码，商家和支付宝签约的产品码，为固定值 QUICK_MSECURITY_PAY
    ],
]);

/**
 * 构建请求字符串并输出.
 */
try {
    $orderStr = $aop->sdkExecute($request);
    header('Content-Type: text/plain');
    echo $orderStr.PHP_EOL;
} catch (\Exception $ex) {
    http_response_code(500);
    print_r($ex);
}
