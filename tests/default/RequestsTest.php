<?php

use Alipay\AlipayRequestFactory;
use PHPUnit\Framework\TestCase;

class RequestsTest extends TestCase
{
    private $apiName = 'alipay.data.bill.balance.query';

    public function testGetterSetter()
    {
        $ins = AlipayRequestFactory::create($this->apiName);
        $this->assertTrue(property_exists($ins, 'notifyUrl'));
        $this->assertNull($ins->getNotifyUrl());
        $url = 'https://foo.bar';
        $ins->setNotifyUrl($url);
        $this->assertEquals($url, $ins->getNotifyUrl());
    }

    public function testSetUnknownProperty()
    {
        $this->expectException('Alipay\Exception\AlipayInvalidPropertyException');

        $ins = AlipayRequestFactory::create($this->apiName);
        $ins->foo;
    }

    public function testGetUnknownProperty()
    {
        $this->expectException('Alipay\Exception\AlipayInvalidPropertyException');
        $ins = AlipayRequestFactory::create($this->apiName);

        try {
            $ins->foo;
        } catch (Alipay\Exception\AlipayInvalidPropertyException $ex) {
            $this->assertEquals('foo', $ex->getProperty());

            throw $ex;
        }
    }

    public function testTimestamp()
    {
        $ins = AlipayRequestFactory::create($this->apiName);
        $ts = $ins->getTimestamp();
        $this->assertRegExp('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $ts);
    }
}
