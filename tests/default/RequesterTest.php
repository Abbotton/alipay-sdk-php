<?php

use Alipay\AlipayCurlRequester;
use Alipay\AlipayRequester;
use PHPUnit\Framework\TestCase;

class RequesterTest extends TestCase
{
    public function testCurl()
    {
        $requester = new AlipayCurlRequester();

        $data = ['foo' => 'bar'];
        $raw = $requester->post('https://httpbin.org/anything', $data);

        $response = json_decode($raw);
        $this->assertEquals('POST', $response->method);
        $this->assertEquals($data['foo'], $response->form->foo);
    }

    public function testExecute()
    {
        $requester = new AlipayRequester(function ($url, $params) {return [$url, $params];});

        $data = ['foo' => 'bar'];
        list($url, $params) = $requester->execute($data);
        $this->assertEquals($requester->getUrl(), $url);
        $this->assertEquals($data['foo'], $params['foo']);
    }
}
