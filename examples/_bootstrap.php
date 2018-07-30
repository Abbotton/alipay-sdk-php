<?php

require __DIR__ . '/../vendor/autoload.php';

use Alipay\AlipayKeyPair;
use Alipay\AlipaySigner;
use Alipay\AopClient;

ini_set('html_errors', '0');

(new Dotenv\Dotenv(__DIR__))->load();

return new AopClient(
    getenv('APP_ID'),
    new AlipaySigner(
        AlipayKeyPair::create(getenv('APP_PRIV_KEY'), getenv('ALIPAY_PUB_KEY'))
    )
);
