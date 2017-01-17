<?php

namespace PhpSDK\Http\Middleware;

/**
 * Class SequenceTest.
 */
final class SequenceTest extends TestCase
{
    public function testStackBuilding()
    {
        $initial = $this->createResponseMock();

        $done = new NullHttpHandler($initial);

        $sequence = new Sequence(
            new Stack(
                $done,
                new NullClientMiddleware(),
                new NullServerMiddleware()
            ),
            $done
        );

        $actual = $sequence->next($this->createServerRequestMock());

        $this->assertSame($initial, $actual);
    }
}
