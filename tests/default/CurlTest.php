<?php

use PHPUnit\Framework\TestCase;
use Alipay\AlipayRequester;

class CurlTest extends TestCase
{
    public function testCurl()
    {
        $requester = new AlipayRequester('https://httpbin.org/anything');

        $data = ['foo' => 'bar'];
        $raw = $requester->execute($data);

        $response = json_decode($raw);
        $this->assertEquals('POST', $response->method);
        $this->assertEquals($requester->getCharset(), $response->args->charset);
        $this->assertEquals('bar', $response->form->foo);
    }
}