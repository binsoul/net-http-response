<?php

namespace BinSoul\Net\Http\Response\Emitter\Target {

    class Headers
    {
        private static $headersSent = false;
        private static $data = [];

        public static function getHeadersSent()
        {
            return self::$headersSent;
        }

        public static function setHeadersSent($value)
        {
            self::$headersSent = $value;
        }

        public static function reset()
        {
            self::$headersSent = false;
            self::$data = [];
        }

        public static function push($header)
        {
            self::$data[] = $header;
        }

        public static function all()
        {
            return self::$data;
        }
    }

    function headers_sent(&$file, &$line)
    {
        $file = 'file';
        $line = 123;

        return Headers::getHeadersSent();
    }

    function header($value)
    {
        Headers::push($value);
    }
}

namespace BinSoul\Test\Net\Http\Response\Emitter\Target {

    use BinSoul\Net\Http\Response\Body;
    use BinSoul\Net\Http\Response\Emitter\Target\Headers;
    use BinSoul\Net\Http\Response\Emitter\Target\SapiTarget;
    use Psr\Http\Message\StreamInterface;

    class SapiTargetTest extends \PHPUnit_Framework_TestCase
    {
        public function tearDown()
        {
            Headers::reset();
            while (ob_get_level() > 1) {
                ob_end_clean();
            }
        }

        /**
         * @expectedException \RuntimeException
         */
        public function test_raises_exception_if_headers_sent()
        {
            Headers::setHeadersSent(true);
            $target = new SapiTarget(false, ob_get_level(), false);
            $target->beginOutput();
        }

        public function test_outputs_headers()
        {
            $target = new SapiTarget(false, ob_get_level(), false);

            $target->beginOutput();
            $target->outputHeader('foo: bar');
            $target->endOutput();

            $this->assertEquals(['foo: bar'], Headers::all());
        }

        public function test_outputs_body_via_echo()
        {
            $body = $this->getMock(Body::class, get_class_methods(Body::class));
            $body->expects($this->any())->method('__toString')->willReturn('foobar');

            $target = new SapiTarget(false, ob_get_level(), false);

            $target->beginOutput();
            $target->outputBody($body);
            $target->endOutput();

            $content = ob_get_contents();
            $this->assertEquals('foobar', $content);

            $this->expectOutputString('foobar');
        }

        public function test_outputs_body_via_output_stream()
        {
            $body = $this->getMock(Body::class, get_class_methods(Body::class));
            $body->expects($this->once())->method('appendTo')->willReturnCallback(
                function () {
                    echo 'foobar';

                    return true;
                }
            );

            ob_start();
            echo 'content';

            $target = new SapiTarget(false, ob_get_level() - 1, true);

            $target->beginOutput();
            $target->outputBody($body);
            $target->endOutput();

            $content = ob_get_contents();
            $this->assertEquals('foobar', $content);

            $this->expectOutputString('foobar');
        }

        public function test_outputs_stream_via_output_stream()
        {
            $body = $this->getMock(StreamInterface::class, get_class_methods(Body::class));
            $body->expects($this->once())->method('__toString')->willReturn('foobar');

            ob_start();
            echo 'content';

            $target = new SapiTarget(false, ob_get_level() - 1, true);

            $target->beginOutput();
            $target->outputBody($body);
            $target->endOutput();

            $content = ob_get_contents();
            $this->assertEquals('foobar', $content);

            $this->expectOutputString('foobar');
        }

        public function test_finishes_response_and_removes_previous_content()
        {
            $body = $this->getMock(StreamInterface::class, get_class_methods(Body::class));
            $body->expects($this->once())->method('__toString')->willReturn('foobar');

            ob_start();
            echo 'content';

            $currentLevel = ob_get_level();
            $target = new SapiTarget(true, $currentLevel - 1, false);

            $target->beginOutput();
            $target->outputBody($body);
            $target->endOutput();

            $this->assertEquals($currentLevel, ob_get_level());
            ob_end_clean();

            $this->expectOutputString('foobar');
        }
    }
}
