<?php

use PHPUnit\Framework\TestCase;
use Alipay\Request\AbstractAlipayRequest;
use Alipay\AlipayRequestFactory;
use Alipay\Request\AlipaySystemOauthTokenRequest;
use Alipay\Exception\AlipayInvalidRequestException;

class RequestFactoryTest extends TestCase
{
    public function testCreate()
    {
        $className = 'AlipaySystemOauthTokenRequest';
        $ins = AlipayRequestFactory::create($className);
        $this->assertInstanceOf(AlipaySystemOauthTokenRequest::class, $ins);
    }

    public function testCreateByApi()
    {
        $apiName = 'alipay.system.oauth.token';
        $ins = AlipayRequestFactory::createByApi($apiName);
        $this->assertInstanceOf(AlipaySystemOauthTokenRequest::class, $ins);
    }

    public function testCreateNotExistedClass()
    {
        $this->expectException(AlipayInvalidRequestException::class);
        $className = 'NotExistedClass';
        $ins = AlipayRequestFactory::create($className);
    }

    public function testCreateInvalidClass()
    {
        $this->expectException(AlipayInvalidRequestException::class);
        $className = 'AbstractAlipayRequest';
        $ins = AlipayRequestFactory::create($className);
    }

    public function testInvalidConfig()
    {
        $this->expectException(AlipayInvalidRequestException::class);
        $className = 'AlipaySystemOauthTokenRequest';
        $ins = AlipayRequestFactory::create($className, [
            'foo' => 'this config does not exist.'
        ]);
    }
}