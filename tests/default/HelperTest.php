<?php

use Alipay\AlipayHelper;
use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    public function testIsEmpty()
    {
        $str = '';
        $res = AlipayHelper::isEmpty($str);
        $this->assertTrue($res);

        $str = '  ';
        $res = AlipayHelper::isEmpty($str);
        $this->assertTrue($res);

        $str = null;
        $res = AlipayHelper::isEmpty($str);
        $this->assertTrue($res);

        $str = 'foo-bar';
        $res = AlipayHelper::isEmpty($str);
        $this->assertFalse($res);
    }
}
