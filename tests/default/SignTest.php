<?php

use PHPUnit\Framework\TestCase;
use Alipay\Signer\AlipaySigner;
use Alipay\Signer\AlipayRSA2Signer;
use Alipay\AlipayKeyPair;

class SignTest extends TestCase
{
    const TEST_DATA = 'foo-bar';

    const PUB_KEY = 'tests/app_public_key.pem';
    
    const PRIV_KEY = 'tests/app_private_key.pem';

    public function testKeyPair()
    {
        $kp = AlipayKeyPair::create(self::PRIV_KEY, self::PUB_KEY);
        $this->assertTrue(true);
        return $kp;
    }

    public function testGenerateKeyPair()
    {
        $kp = AlipayKeyPair::generate();
        $this->assertTrue(true);
        return $kp;
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidKeyException
     */
    public function testInvalidKey()
    {
        AlipayKeyPair::create(self::PUB_KEY, self::PRIV_KEY);
    }

    /**
     * @depends testKeyPair
     */
    public function testClone(AlipayKeyPair $kp)
    {
        $kpCopy = clone $kp;
        $this->assertInstanceOf(get_class($kp), $kpCopy);
        unset($kpCopy);
        $this->assertTrue(is_resource($kp->getPublicKey()));
        $this->assertTrue(is_resource($kp->getPrivateKey()));
        return $kp;
    }

    // =================================

    public function testCreate()
    {
        $signer = new AlipayRSA2Signer();
        $this->assertEquals('RSA2', $signer->getSignType());
        $this->assertInstanceOf('Alipay\Signer\AlipaySigner', $signer);
        return $signer;
    }

    /**
     * @depends testCreate
     * @depends testKeyPair
     */
    public function testGenerate(AlipaySigner $signer, AlipayKeyPair $keyPair)
    {
        $sign = $signer->generate(self::TEST_DATA, $keyPair->getPrivateKey());
        $this->assertNotFalse(base64_decode($sign));
        return $sign;
    }

    /**
     * @depends testCreate
     * @depends testKeyPair
     * @depends testGenerate
     */
    public function testVerify(AlipaySigner $signer, AlipayKeyPair $keyPair, $sign)
    {
        $signer->verify($sign, self::TEST_DATA, $keyPair->getPublicKey());
        $this->assertTrue(true);
    }

    /**
     * @depends testCreate
     * @depends testKeyPair
     * @expectedException Alipay\Exception\AlipayBase64Exception
     */
    public function testInvalidBase64Data(AlipaySigner $signer, AlipayKeyPair $keyPair)
    {
        $signer->verify('this is an undecodable sign ...', self::TEST_DATA, $keyPair->getPublicKey());
    }

    /**
     * @depends testCreate
     * @depends testKeyPair
     * @depends testGenerate
     * @expectedException Alipay\Exception\AlipayInvalidSignException
     */
    public function testInvalidSign(AlipaySigner $signer, AlipayKeyPair $keyPair, $sign)
    {
        $signer->verify($sign, 'this is a string has been tampered with', $keyPair->getPublicKey());
    }

    /**
     * @depends testCreate
     * @depends testKeyPair
     */
    public function testSignParams(AlipaySigner $signer, AlipayKeyPair $keyPair)
    {
        $data = ['foo' => 'bar', 'bar' => 'foo', 'empty' => ''];
        $sign = $signer->generateByParams($data, $keyPair->getPrivateKey());
        $params = [
            'sign' => $sign,
            'sign_type' => 'RSA2'
        ];
        $params = array_merge($params, $data);
        $signer->verifyByParams($params, $keyPair->getPublicKey());
        $this->assertTrue(true);
    }
}