<?php

use PHPUnit\Framework\TestCase;
use Alipay\AlipaySigner;

class SignTest extends TestCase
{
    const PUB_KEY = 'tests/app_public_key.pem';
    
    const PRIV_KEY = 'tests/app_private_key.pem';

    const TEST_DATA = 'foo-bar';

    const SIGN_TYPE = 'RSA2';

    public function testCreate()
    {
        $signer = AlipaySigner::create(self::PRIV_KEY, self::PUB_KEY, self::SIGN_TYPE);
        $this->assertEquals(self::SIGN_TYPE, $signer->getSignType());
        $this->assertInstanceOf('Alipay\AlipaySigner', $signer);
        return $signer;
    }

    public function testCreateWithStringKeys()
    {
        $signer = AlipaySigner::create(file_get_contents(self::PRIV_KEY), file_get_contents(self::PUB_KEY));
        $this->assertInstanceOf('Alipay\AlipaySigner', $signer);
        return $signer;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnknownSignType()
    {
        AlipaySigner::create(self::PRIV_KEY, self::PUB_KEY, 'this is an unknown sign type');
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidKeyException
     */
    public function testInvalidKey()
    {
        AlipaySigner::create(self::PUB_KEY, self::PRIV_KEY);
    }

    /**
     * @depends testCreate
     */
    public function testClone(AlipaySigner $signer)
    {
        $anotherHelper = clone $signer;
        $this->assertInstanceOf(get_class($signer), $anotherHelper);
        unset($anotherHelper);
        return $signer;
    }

    /**
     * @depends testClone
     */
    public function testGenerate(AlipaySigner $signer)
    {
        $sign = $signer->generate(self::TEST_DATA);
        $this->assertNotFalse(base64_decode($sign));
        return $sign;
    }

    /**
     * @depends testCreate
     * @depends testGenerate
     */
    public function testVerify(AlipaySigner $signer, $sign)
    {
        $signer->verify($sign, self::TEST_DATA);
        $this->assertTrue(true);
    }

    /**
     * @depends testCreate
     * @expectedException Alipay\Exception\AlipayBase64Exception
     */
    public function testInvalidBase64Data(AlipaySigner $signer)
    {
        $signer->verify('this is an undecodable data ...', self::TEST_DATA);
    }

    /**
     * @depends testCreate
     * @depends testGenerate
     * @expectedException Alipay\Exception\AlipayInvalidSignException
     */
    public function testInvalidSign(AlipaySigner $signer, $sign)
    {
        $signer->verify($sign, 'this is a string has been tampered with');
        $this->assertTrue(true);
    }

    /**
     * @depends testCreate
     */
    public function testSignParams(AlipaySigner $signer)
    {
        $data = ['foo' => 'bar', 'bar' => 'foo', 'empty' => ''];
        $sign = $signer->generateByParams($data);
        $params = [
            'sign' => $sign,
            'sign_type' => $signer->getSignType()
        ];
        $params = array_merge($params, $data);
        $signer->verifyByAsyncCallback($params);
        $this->assertTrue(true);
    }
}