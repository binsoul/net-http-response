<?php

namespace BinSoul\Test\Net\Http\Response\Body;

use BinSoul\Net\Http\Response\Body;
use BinSoul\Net\Http\Response\Body\BodyDecorator;

class BodyDecoratorImplementation implements Body
{
    use BodyDecorator;
}

class BodyDecoratorTest extends \PHPUnit_Framework_TestCase
{
    public function interfaceMethod()
    {
        $iFooRef = new \ReflectionClass(Body::class);
        $methods = $iFooRef->getMethods();

        $result = [];
        foreach ($methods as $method) {
            if ($method->getDeclaringClass()->getName() != Body::class) {
                continue;
            }

            $mockedParameters = [];
            $parameters = $method->getParameters();
            foreach ($parameters as $parameter) {
                if ($parameter->isOptional()) {
                    break;
                }
                $mock = $this->getMock($parameter->getClass()->getName());
                $mockedParameters[] = $mock;
            }

            $result[] = [$method->getName(), $mockedParameters];
        }

        return $result;
    }

    /**
     * @param string $method
     * @dataProvider interfaceMethod
     */
    public function test_implements_interface_method($method, $parameters)
    {
        $body = $this->getMock(Body::class);
        $body->expects($this->once())->method($method);

        $decorator = new BodyDecoratorImplementation($body);
        call_user_func_array([$decorator, $method], $parameters);
    }
}
