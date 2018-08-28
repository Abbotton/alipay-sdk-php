<?php
/**
 * 本例展示如何使用 PHP 生成密钥对
 */

require __DIR__ . '/../../vendor/autoload.php';

use Alipay\Key\AlipayKeyPair;

$keyPair = AlipayKeyPair::generate();
echo $keyPair->getPrivateKey()->asString();
echo $keyPair->getPublicKey()->asString();