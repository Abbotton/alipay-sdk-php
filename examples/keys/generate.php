<?php
/**
 * 本例展示如何使用 PHP 生成密钥对
 */

require __DIR__ . '/../../vendor/autoload.php';

use Alipay\Key\AlipayKeyPair;

$configargs = [
    // 若报错请尝试取消下列注释并根据实际情况配置
    // 'config' => __DIR__ . '/openssl.cnf',
];

$keyPair = AlipayKeyPair::generate($configargs);
echo $keyPair->getPrivateKey()->asString($configargs);
echo $keyPair->getPublicKey()->asString();