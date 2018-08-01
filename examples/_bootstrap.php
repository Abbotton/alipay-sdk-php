<?php

require __DIR__ . '/../vendor/autoload.php';

use Alipay\Key\AlipayKeyPair;
use Alipay\AopClient;

ini_set('html_errors', '0');
ini_set('error_log', 'logs/php-error.log');

(new Dotenv\Dotenv(__DIR__))->load();

return new AopClient(
    getenv('APP_ID'),
    AlipayKeyPair::create(getenv('APP_PRIV_KEY'), getenv('ALIPAY_PUB_KEY'))
);
