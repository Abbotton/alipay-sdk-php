<?php

use PHPUnit\Framework\TestCase;
use Alipay\Request\AbstractAlipayRequest;

class RequestTest extends TestCase
{
    public function testRequests()
    {
        $list = require __DIR__ . '/../vendor/composer/autoload_classmap.php';
        foreach($list as $k => $v)
        {
            $class = new ReflectionClass($k);
            if($class->isSubclassOf(AbstractAlipayRequest::className()) === false)
            {
                continue;
            }
            if($class->isAbstract())
            {
                continue;
            }

            // ------------------------------------------------------------

            /** @var AbstractAlipayRequest $ins */
            $ins = new $k([
                'notifyUrl' => 'notify_url'
            ]);
            $this->assertEquals('notify_url', $ins->notifyUrl);
            $this->assertTrue(isset($ins->notifyUrl));
            unset($ins->notifyUrl);
            $this->assertFalse(isset($ins->notifyUrl));

            // ------------------------------------------------------------

            $ins = new $k();
            $ins->notifyUrl = 'notify_url';
            $ins->returnUrl = 'return_url';
            $ins->terminalType = 'terminal_type';
            $ins->terminalInfo = 'terminal_info';
            $ins->prodCode = 'prod_code';
            $this->assertEquals('notify_url', $ins->notifyUrl);
            $this->assertEquals('return_url', $ins->returnUrl);
            $this->assertEquals('terminal_type', $ins->terminalType);
            $this->assertEquals('terminal_info', $ins->terminalInfo);
            $this->assertEquals('prod_code', $ins->prodCode);

            // ------------------------------------------------------------

            $this->assertNotEmpty($ins->getApiMethodName());
            $this->assertEquals($ins->getApiVersion(), '1.0');
            $this->assertTrue(is_array($ins->getApiParams()));

            // ------------------------------------------------------------

            $methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach($methods as $method)
            {
                /** @var ReflectionMethod $method */
                $funcName = $method->getName();
                $propName = substr($funcName, 3);
                $funPrefix = substr($funcName, 0, 3);

                if($funPrefix !== 'set') {
                    continue;
                }

                $value = uniqid();
                $method->invoke($ins, $value);

                $getter = $class->getMethod('get' . $propName);
                $this->assertEquals($value, $getter->invoke($ins));
            }
        }
    }
}
