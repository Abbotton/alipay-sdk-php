<?php

use Alipay\AopClient;
use Alipay\Key\AlipayKeyPair;
use Alipay\Request\AbstractAlipayRequest;
use Alipay\Signer\AlipayRSA2Signer;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    const APPID = '123456';

    /**
     * @depends SignTest::testKeyPair
     */
    public function testCreate(AlipayKeyPair $keyPair)
    {
        $aop = new AopClient(static::APPID, $keyPair);
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

    /**
     * @depends testCreate
     * @depends SignTest::testGenerate
     */
    public function testVerify(AopClient $client, $sign)
    {
        parse_str(SignTest::TEST_DATA, $params);
        $params['sign'] = $sign;
        $params['sign_type'] = (new AlipayRSA2Signer)->getSignType();
        $result = $client->verify($params);
        $this->assertTrue($result);

        $params['sign'] = '123';
        $result = $client->verify($params);
        $this->assertFalse($result);
    }

    /**
     * @depends testCreate
     */
    public function testDecrypt(AopClient $client)
    {
        $result = $client->decrypt(
            'h6qnRCccN8vR/IPtKYuTMIZUoDE9ZKp6pB2rrh4BMu8C7rS51ZZFn3aTlPl4i/RxvdS7SkJ+i49uDYfV+u5CKA==',
            'ZXdCNW9ta1FsRGlVbzQ0TVdXbzJKN001dA=='
        );

        $this->assertEquals('{"code":"1000","msg":"success","mobile":"12777207727"}', $result);
    }

    /**
     * @depends testCreate
     */
    public function testDecryptException(AopClient $client)
    {
        $this->expectException('Alipay\Exception\AlipayOpenSslException');
        
        $client->decrypt(
            'h6qnRCccN8vR/IPtKYuTMIZUoDE9ZKp6pB2rrh4BMu8C7rS51ZZFn3aTlPl4i/RxvdS7SkJ+i49uDYfV+u5CKA==',
            'ZXdCNW9ta1FsRGlVbzQ0TVdXbzJKN001dA==',
            'non-existed-cipher'
        );
    }

    /**
     * @depends testCreate
     */
    public function testGetters(AopClient $client)
    {
        $this->assertInstanceOf('Alipay\Signer\AlipaySigner', $client->getSigner());
        $this->assertInstanceOf('Alipay\Key\AlipayKeyPair', $client->getKeyPair());
        $this->assertInstanceOf('Alipay\AlipayResponseFactory', $client->getParser());
        $this->assertInstanceOf('Alipay\AlipayRequester', $client->getRequester());
        $this->assertEquals(static::APPID, $client->getAppId());
    }
}
