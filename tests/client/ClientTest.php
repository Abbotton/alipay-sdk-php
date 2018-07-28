<?php

use PHPUnit\Framework\TestCase;
use Alipay\Request\AlipaySystemOauthTokenRequest;
use Alipay\AopClient;
use Alipay\AlipaySigner;
use Alipay\Request\AbstractAlipayRequest;
use Alipay\AlipayRequester;

class ClientTest extends TestCase
{
    const APPID = '123456';
    /**
     * @depends SignTest::testCreate
     */
    public function testCreate(AlipaySigner $signer)
    {
        $aop = new AopClient(static::APPID, $signer);
        $this->assertTrue(true);
        return $aop;
    }

    /**
     * @depends testCreate
     * @depends RequestFactoryTest::testCreate
     */
    public function testBuild(AopClient $client, AbstractAlipayRequest $request)
    {
        $params = $client->build($request);
        $this->assertEquals('foo', $params['code']);
        $this->assertEquals(static::APPID, $params['app_id']);
    }

    /**
     * @depends testCreate
     * @depends RequestFactoryTest::testCreate
     */
    public function testSdkExecute(AopClient $client, AbstractAlipayRequest $request)
    {
        $res = $client->sdkExecute($request);
        $this->assertNotEmpty($res);

        $res = $client->pageExecuteUrl($request);
        $this->assertNotEmpty($res);

        $res = $client->pageExecuteForm($request);
        $this->assertNotEmpty($res);
    }
    

}