<?php

namespace Alipay\Example;

use Alipay\AopClient;
use Alipay\AlipayRequestFactory;


require '../vendor/autoload.php';

ini_set('html_errors', false);

$aop = AopClient::create(
    '2018071660720249',
    'RSA2',
    '../tests/app_private_key.pem',
    '../tests/ali_public_key.pem'
);

$request = AlipayRequestFactory::createByApi('alipay.system.oauth.token');
$request->setGrantType('authorization_code');
$request->setCode($_GET['authcode']);


$result = $aop->execute($request);

var_dump($result);