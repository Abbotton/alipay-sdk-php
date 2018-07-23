<?php

use Alipay\Exception\AlipayInvalidPropertyException;
use Alipay\Request\AbstractAlipayRequest;
use Alipay\Request\AlipaySystemOauthTokenRequest;
use PHPUnit\Framework\TestCase;

class RequestsTest extends TestCase
{
    public function testRequests()
    {
        $list = require __DIR__ . '/../vendor/composer/autoload_classmap.php';
        foreach ($list as $k => $v) {
            $class = new ReflectionClass($k);
            if ($class->isSubclassOf(AbstractAlipayRequest::className()) === false) {
                continue;
            }
            if ($class->isAbstract()) {
                continue;
            }

            /** @var AbstractAlipayRequest $ins */
            $ins = new $k();
            $this->assertNotEmpty($ins->getApiMethodName());
            $this->assertEquals($ins->getApiVersion(), '1.0');
            $this->assertTrue(is_array($ins->getApiParams()));

            // ------------------------------------------------------------

            $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach ($methods as $method) {
                /** @var ReflectionMethod $method */
                $funcName = $method->getName();
                $propName = substr($funcName, 3);
                $funPrefix = substr($funcName, 0, 3);

                if ($funPrefix !== 'set') {
                    continue;
                }

                $value = uniqid();
                $ins->$propName = $value;
                $this->assertEquals($value, $ins->$propName);
            }
        }
    }

    public function testGetterSetter()
    {
        $ins = new AlipaySystemOauthTokenRequest([
            'notifyUrl' => 'notify_url',
        ]);
        $this->assertTrue(isset($ins->notifyUrl));
        $this->assertEquals('notify_url', $ins->notifyUrl);
        unset($ins->notifyUrl);
        $this->assertFalse(isset($ins->notifyUrl));
        $this->assertFalse(isset($ins->foo));
    }

    public function testSetUnknownProperty()
    {
        $this->expectException(AlipayInvalidPropertyException::class);
        $req = new AlipaySystemOauthTokenRequest();
        $req->foo = 'this property does not exist';
    }

    public function testGetUnknownProperty()
    {
        $this->expectException(AlipayInvalidPropertyException::class);
        $req = new AlipaySystemOauthTokenRequest();
        $value = $req->foo;
    }
}
