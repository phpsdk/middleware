<?php

namespace PhpSDK\Http\Middleware;

use SplQueue;

/**
 * Class StackTest.
 */
final class StackTest extends TestCase
{
    /**
     * @test
     */
    public function buildStack()
    {
        $foo = new NullClientMiddleware();
        $bar = new NullServerMiddleware();

        $initial = new Stack(new NullHttpHandler($this->createResponseMock()), $foo);

        $this->assertCount(1, $initial);

        $actual = $initial
            ->withMiddleware($foo)
        ;

        $this->assertCount(1, $initial);
        $this->assertSame($initial, $actual);

        $actual = $initial
            ->withMiddleware($foo)
            ->withMiddleware($bar)
        ;

        $this->assertNotSame($initial, $actual);
        $this->assertCount(2, $actual);

        $actual = $initial
            ->withoutMiddleware($bar)
        ;

        $this->assertCount(1, $initial);
        $this->assertSame($initial, $actual);

        $actual = $initial
            ->withoutMiddleware($foo)
            ->withoutMiddleware($bar)
        ;

        $this->assertNotSame($initial, $actual);
        $this->assertCount(0, $actual);
    }

    /**
     * @test
     */
    public function processStack()
    {
        $history = new SplQueue();

        $initial = $this->createResponseMock();

        $foo = new StubMiddleware($history);
        $bar = new StubMiddleware($history);
        $baz = new StubMiddleware($history);

        $stack = new Stack(new NullHttpHandler($initial), $foo, $bar, $baz);

        $actual = $stack->process($this->createRequestMock());

        $this->assertSame($initial, $actual);

        $sequence = [$foo, $bar, $baz, $baz, $bar, $foo];

        $this->assertCount(count($sequence), $history);
        $this->assertSame($sequence, iterator_to_array($history));

        $actual = $stack->process($this->createRequestMock());

        $this->assertSame($initial, $actual);

        $sequence = array_merge($sequence, $sequence);

        $this->assertCount(count($sequence), $history);
        $this->assertSame($sequence, iterator_to_array($history));
    }
}
