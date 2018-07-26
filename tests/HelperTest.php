<?php

use PHPUnit\Framework\TestCase;
use Alipay\Request\AbstractAlipayRequest;
use Alipay\AlipayHelper;

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

    public function testCamelCase()
    {
        $str = 'foo.bar';
        $res = AlipayHelper::camelCase($str, '.');
        $this->assertEquals('fooBar', $res);
    }

    public function testStudlyCase()
    {
        $str = 'foo.bar';
        $res = AlipayHelper::studlyCase($str, '.');
        $this->assertEquals('FooBar', $res);
    }

    public function testCurl()
    {
        $url = 'https://httpbin.org/anything?bar=foo';
        $data = ['foo' => 'bar'];
        $response = AlipayHelper::curl($url, $data);
        $response = json_decode($response);
        
        $this->assertEquals('POST', $response->method);
        $this->assertEquals('foo', $response->args->bar);
        $this->assertEquals('bar', $response->form->foo);
    }

    public function testExecute()
    {
        
    }
}