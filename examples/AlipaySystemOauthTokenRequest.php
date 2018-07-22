<?php

namespace Alipay\Example;

use Alipay\AlipayRequestFactory;
use Alipay\AlipaySign;
use Alipay\AopClient;

require '../vendor/autoload.php';

ini_set('html_errors', false);

$signHelper = AlipaySign::create(
    '../tests/app_private_key.pem',
    '../tests/ali_public_key.pem'
);

$aop = new AopClient('2018071660720249', $signHelper);

$request = AlipayRequestFactory::createByApi('alipay.system.oauth.token');
$request->setGrantType('authorization_code');
$request->setCode($_GET['authcode']);

try {
    $result = $aop->execute($request)->getData();
} catch (\Exception $ex) {
    print_r($ex);
    exit;
}

print_r($result);