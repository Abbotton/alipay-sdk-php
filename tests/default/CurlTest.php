<?php

use PHPUnit\Framework\TestCase;
use Alipay\AlipayRequester;
use Alipay\AlipayCurlRequester;

class CurlTest extends TestCase
{
    public function testCurl()
    {
        $requester = new AlipayCurlRequester();

        $data = ['foo' => 'bar'];
        $raw = $requester->post('https://httpbin.org/anything', $data);

        $response = json_decode($raw);
        $this->assertEquals('POST', $response->method);
        $this->assertEquals('bar', $response->form->foo);
    }

    public function testExecute()
    {
        $requester = new AlipayRequester(function ($url, $params){return [$url, $params];});
        $response = $requester->execute(['foo' => 'bar']);
        $this->assertEquals($requester->getUrl(), $response[0]);
        $this->assertEquals('bar', $response[1]['foo']);
    }
}