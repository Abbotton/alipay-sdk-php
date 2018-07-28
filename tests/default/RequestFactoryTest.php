<?php

use PHPUnit\Framework\TestCase;
use Alipay\Request\AbstractAlipayRequest;
use Alipay\AlipayRequestFactory;
use Alipay\Request\AlipaySystemOauthTokenRequest;

class RequestFactoryTest extends TestCase
{
    public function testCreate()
    {
        $className = AlipaySystemOauthTokenRequest::className(true);
        $ins = AlipayRequestFactory::create($className, ['code' => 'foo']);
        $this->assertEquals('foo', $ins->getCode());
        return $ins;
    }

    public function testCreateByApi()
    {
        $apiName = 'alipay.system.oauth.token';
        $ins = AlipayRequestFactory::createByApi($apiName);
        $this->assertInstanceOf(AlipaySystemOauthTokenRequest::className(), $ins);
        return $ins;
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidRequestException
     */
    public function testCreateNotExistedClass()
    {
        $className = 'NotExistedClass';
        $ins = AlipayRequestFactory::create($className);
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidRequestException
     */
    public function testCreateInvalidClass()
    {
        $className = 'AbstractAlipayRequest';
        $ins = AlipayRequestFactory::create($className);
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidRequestException
     */
    public function testInvalidConfig()
    {
        $className = 'AlipaySystemOauthTokenRequest';
        $ins = AlipayRequestFactory::create($className, [
            'foo' => 'this config does not exist'
        ]);
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidRequestException
     */
    public function testUnwritableConfig()
    {
        $className = 'AlipaySystemOauthTokenRequest';
        $ins = AlipayRequestFactory::create($className, [
            'apiParams' => 'this config could not be written'
        ]);
    }
}