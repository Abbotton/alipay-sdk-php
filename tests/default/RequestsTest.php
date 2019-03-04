<?php

use Alipay\Request\AbstractAlipayRequest;
use Alipay\Request\AlipaySystemOauthTokenRequest;
use PHPUnit\Framework\TestCase;

class RequestsTest extends TestCase
{
    public function testRequests()
    {
        $list = glob('aop/Request/*Request.php');
        foreach ($list as $v) {
            $className = 'Alipay\\Request\\' . basename($v, '.php');
            $class = new ReflectionClass($className);
            if ($class->isAbstract()) {
                continue;
            }

            /** @var AbstractAlipayRequest $ins */
            $ins = new $className();
            $this->assertNotEmpty($ins->getApiMethodName());
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
        $this->expectException('Alipay\Exception\AlipayInvalidPropertyException');

        $req = new AlipaySystemOauthTokenRequest();
        $req->foo = 'this property does not exist';
    }

    public function testGetUnknownProperty()
    {
        $this->expectException('Alipay\Exception\AlipayInvalidPropertyException');
        $req = new AlipaySystemOauthTokenRequest();

        try {
            $req->foo;
        } catch (Alipay\Exception\AlipayInvalidPropertyException $ex) {
            $this->assertEquals('foo', $ex->getProperty());
            throw $ex;
        }
    }

    public function testTimestamp()
    {
        $req = new AlipaySystemOauthTokenRequest();
        $ts = $req->getTimestamp();
        $this->assertRegExp('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $ts);
    }
}
