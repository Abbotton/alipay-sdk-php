<?php

use PHPUnit\Framework\TestCase;
use Alipay\Signer\AlipaySigner;
use Alipay\Signer\AlipayRSA2Signer;
use Alipay\Key\AlipayKeyPair;
use Alipay\Exception\AlipayInvalidSignException;

class SignTest extends TestCase
{
    const TEST_DATA = 'foo=abc&bar=100&empty=&not_empty=0';

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
        $this->assertTrue(
            $kp->getPrivateKey()->isLoaded()
        );
        $this->assertTrue(
            $kp->getPublicKey()->isLoaded()
        );
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
        $key = $kp->getPrivateKey();
        $keyCopy = clone $kp->getPrivateKey();

        $this->assertInstanceOf(get_class($key), $keyCopy);
        unset($keyCopy);
        $this->assertTrue($key->isLoaded());
    }

    /**
     * @depends testKeyPair
     */
    public function testSerialize(AlipayKeyPair $kp)
    {
        $ser = serialize($kp);
        unset($kp);
        $kp = unserialize($ser);
        $this->assertTrue($kp->getPublicKey()->isLoaded());
        $this->assertTrue($kp->getPrivateKey()->isLoaded());
        return $kp;
    }

    // ==================================================================

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
        parse_str(self::TEST_DATA, $params);
        $sign = $signer->generateByParams($params, $keyPair->getPrivateKey()->asResource());
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
        parse_str(self::TEST_DATA, $params);
        $params['sign'] = $sign;
        $params['sign_type'] = (new AlipayRSA2Signer)->getSignType();
        $signer->verifyByParams($params, $keyPair->getPublicKey()->asResource());
        $this->assertTrue(true);
    }

    /**
     * @depends testCreate
     * @depends testKeyPair
     * @expectedException Alipay\Exception\AlipayBase64Exception
     */
    public function testInvalidBase64Data(AlipaySigner $signer, AlipayKeyPair $keyPair)
    {
        $signer->verify('this is an undecodable sign ...', self::TEST_DATA, $keyPair->getPublicKey()->asResource());
    }

    /**
     * @depends testCreate
     * @depends testKeyPair
     * @depends testGenerate
     * @expectedException Alipay\Exception\AlipayInvalidSignException
     */
    public function testInvalidSign(AlipaySigner $signer, AlipayKeyPair $keyPair, $sign)
    {
        try {
            $data = 'this is a string has been tampered with';
            $signer->verify($sign, $data, $keyPair->getPublicKey()->asResource());
        } catch (\Alipay\Exception\AlipayInvalidSignException $ex) {
            $this->assertEquals($data, $ex->getData());
            $this->assertEquals($sign, $ex->getSign());
            throw $ex;
        }
    }
}