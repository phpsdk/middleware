<?php

namespace PhpSDK\Http\Middleware;

use SplQueue;

/**
 * Class StackBuilderTest.
 */
final class StackBuilderTest extends TestCase
{
    public function testStackBuilding()
    {
        $history = new SplQueue();

        $initial = $this->createResponseMock();

        $foo = new StubMiddleware($history);
        $bar = new StubMiddleware($history);
        $baz = new StubMiddleware($history);

        $builder = new DefaultStackBuilder(new NullHttpHandler($initial));

        $builder
            ->add($foo, $bar, $baz)
            ->remove($foo, $bar)
            ->add($foo)
        ;

        $stack = $builder->getStack();

        $this->assertCount(2, $stack);

        $actual = $stack->process($this->createRequestMock());

        $this->assertSame($initial, $actual);

        $sequence = [$baz, $foo, $foo, $baz];

        $this->assertCount(count($sequence), $history);
        $this->assertSame($sequence, iterator_to_array($history));
    }
}
