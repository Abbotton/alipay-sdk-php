<?php

require '../vendor/autoload.php';

use Alipay\AlipaySign;
use Alipay\AopClient;

ini_set('html_errors', false);

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$alipay_sign = AlipaySign::create(
    getenv('APP_PRIV_KEY'),
    getenv('ALIPAY_PUB_KEY')
);

$aop = new AopClient(getenv('APP_ID'), $alipay_sign);