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

    /**
     * @expectedException Alipay\Exception\AlipayInvalidRequestException
     * @expectedExceptionMessage exist
     */
    public function testCreateNotExistedClass()
    {
        $className = 'NotExistedClass';
        $ins = AlipayRequestFactory::create($className);
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidRequestException
     * @expectedExceptionMessage extend
     */
    public function testCreateInvalidClass()
    {
        $className = AbstractAlipayRequest::className(true);
        $ins = AlipayRequestFactory::create($className);
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidRequestException
     */
    public function testInvalidConfig()
    {
        $className = AlipaySystemOauthTokenRequest::className(true);
        $ins = AlipayRequestFactory::create($className, [
            'foo' => 'this config does not exist'
        ]);
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidRequestException
     */
    public function testUnwritableConfig()
    {
        $className = AlipaySystemOauthTokenRequest::className(true);
        $ins = AlipayRequestFactory::create($className, [
            'apiParams' => 'this config could not be written'
        ]);
    }
}