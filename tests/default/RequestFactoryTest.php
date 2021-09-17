<?php

use Alipay\AlipayRequestFactory;
use Alipay\Request\AlipayRequest;
use PHPUnit\Framework\TestCase;

class RequestFactoryTest extends TestCase
{
    private $apiName = 'alipay.data.bill.balance.query';

    public function testStaticCreate()
    {
        $this->assertNotNull(
            AlipayRequestFactory::create($this->apiName)
        );
    }

    public function testCreateByApi()
    {
        $ins = AlipayRequestFactory::create($this->apiName);
        $this->assertInstanceOf(AlipayRequest::className(), $ins);
    }
}
