<?php

use PHPUnit\Framework\TestCase;
use Alipay\AlipaySign;

class SignTest extends TestCase
{
    private static $PUB_KEY = __DIR__ . '/app_public_key.pem';
    
    private static $PRIV_KEY = __DIR__ . '/app_private_key.pem';

    const TEST_DATA = 'foo-bar';

    const SIGN_TYPE = 'RSA2';

    public function testCreate()
    {
        $helper = AlipaySign::create(self::$PRIV_KEY, self::$PUB_KEY, self::SIGN_TYPE);
        $this->assertEquals(self::SIGN_TYPE, $helper->getSignType());
        $this->assertInstanceOf('Alipay\AlipaySign', $helper);
        return $helper;
    }

    public function testCreateWithStringKeys()
    {
        $helper = AlipaySign::create(file_get_contents(self::$PRIV_KEY), file_get_contents(self::$PUB_KEY));
        $this->assertInstanceOf('Alipay\AlipaySign', $helper);
        return $helper;
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testUnknownSignType()
    {
        AlipaySign::create(self::$PRIV_KEY, self::$PUB_KEY, 'this is an unknown sign type');
    }

    /**
     * @expectedException Alipay\Exception\AlipayInvalidKeyException
     */
    public function testInvalidKey()
    {
        AlipaySign::create(self::$PUB_KEY, self::$PRIV_KEY);
    }

    /**
     * @depends testCreate
     */
    public function testClone(AlipaySign $helper)
    {
        $anotherHelper = clone $helper;
        $this->assertInstanceOf(get_class($helper), $anotherHelper);
        unset($anotherHelper);
        return $helper;
    }

    /**
     * @depends testClone
     */
    public function testGenerate(AlipaySign $helper)
    {
        $sign = $helper->generate(self::TEST_DATA);
        $this->assertNotFalse(base64_decode($sign));
        return $sign;
    }

    /**
     * @depends testCreate
     * @depends testGenerate
     */
    public function testVerify(AlipaySign $helper, $sign)
    {
        $helper->verify($sign, self::TEST_DATA);
        $this->assertTrue(true);
    }

    /**
     * @depends testCreate
     * @expectedException Alipay\Exception\AlipayBase64Exception
     */
    public function testInvalidBase64Data(AlipaySign $helper)
    {
        $helper->verify('this is an undecodable data ...', self::TEST_DATA);
    }

    /**
     * @depends testCreate
     * @depends testGenerate
     * @expectedException Alipay\Exception\AlipayInvalidSignException
     */
    public function testInvalidSign(AlipaySign $helper, $sign)
    {
        $helper->verify($sign, 'this is a string has been tampered with');
        $this->assertTrue(true);
    }

    /**
     * @depends testCreate
     */
    public function testSignParams(AlipaySign $helper)
    {
        $data = ['foo' => 'bar', 'bar' => 'foo', 'empty' => ''];
        $sign = $helper->generateByParams($data);
        $params = [
            'sign' => $sign,
            'sign_type' => $helper->getSignType()
        ];
        $params = array_merge($params, $data);
        $helper->verifyByAsyncCallback($params);
        $this->assertTrue(true);
    }
}