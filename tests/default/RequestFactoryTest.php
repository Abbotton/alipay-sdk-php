<?php

use PHPUnit\Framework\TestCase;
use Alipay\Request\AbstractAlipayRequest;
use Alipay\AlipayRequestFactory;
use Alipay\Request\AlipaySystemOauthTokenRequest;

class RequestFactoryTest extends TestCase
{
    protected function provideRequestClassName()
    {
        return AlipaySystemOauthTokenRequest::className(true);
    }

    public function testCreate()
    {
        $className = $this->provideRequestClassName();
        $ins = (new AlipayRequestFactory)->create($className, ['code' => 'foo']);
        $this->assertEquals('foo', $ins->getCode());
        return $ins;
    }

    public function testStaticCreate()
    {
        $className = $this->provideRequestClassName();
        $this->assertNotNull(
            AlipayRequestFactory::create($className)
        );
    }

    public function testCreateByApi()
    {
        $apiName = 'alipay.system.oauth.token';
        $ins = AlipayRequestFactory::create($apiName);
        $this->assertInstanceOf(AlipaySystemOauthTokenRequest::className(), $ins);
        return $ins;
    }

    public function testCreateNotExistedClass()
    {
        $this->expectException('Alipay\Exception\AlipayInvalidRequestException');
        $this->expectExceptionMessage('exist');

        $className = 'NotExistedClass';
        $ins = AlipayRequestFactory::create($className);
    }

    public function testCreateInvalidClass()
    {
        $this->expectException('Alipay\Exception\AlipayInvalidRequestException');
        $this->expectExceptionMessage('extend');

        $className = AbstractAlipayRequest::className(true);
        $ins = AlipayRequestFactory::create($className);
    }

    public function testInvalidConfig()
    {
        $this->expectException('Alipay\Exception\AlipayInvalidRequestException');

        $className = AlipaySystemOauthTokenRequest::className(true);
        $ins = AlipayRequestFactory::create($className, [
            'foo' => 'this config does not exist'
        ]);
    }

    public function testUnwritableConfig()
    {
        $this->expectException('Alipay\Exception\AlipayInvalidRequestException');

        $className = AlipaySystemOauthTokenRequest::className(true);
        $ins = AlipayRequestFactory::create($className, [
            'apiParams' => 'this config could not be written'
        ]);
    }
}
