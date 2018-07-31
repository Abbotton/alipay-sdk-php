<?php

use Alipay\AlipayRequestFactory;

$aop = require __DIR__ . '/_bootstrap.php';

/**
 * 根据文件名剥离请求名
 */
$apiName = basename(__FILE__, '.php');

/**
 * 构建请求类
 */
$request = AlipayRequestFactory::create($apiName, [
    'auth_token' => $argv[1]
]);

/**
 * 发起请求并调试输出结果
 */
try {
    $data = $aop->execute($request)->getData();
    print_r($data);
} catch (\Exception $ex) {
    http_response_code(500);
    print_r($ex);
}