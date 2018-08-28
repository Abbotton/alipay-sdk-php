<?php
/**
 * 本例展示如何使用 PHP 生成密钥对
 */

require __DIR__ . '/../../vendor/autoload.php';

use Alipay\Key\AlipayKeyPair;
use Alipay\Key\AlipayPrivateKey;

$configargs = [
    // 若报错请尝试取消下列注释并根据实际情况配置
    // 'config' => __DIR__ . '/openssl.cnf',
];

// 生成密钥对
$keyPair = AlipayKeyPair::generate($configargs);

// 输出公钥
echo $keyPair->getPublicKey()->asString();

// 输出私钥（通常情况下不需要配置 $configargs 则可使用如同公钥的方式转字符串）
echo AlipayPrivateKey::toString($keyPair->getPrivateKey()->asResource(), $configargs);