<?php

namespace PhpSDK\Http\Middleware;

use Iterator;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Middleware\ClientMiddlewareInterface;
use Psr\Http\Middleware\DelegateInterface;
use Psr\Http\Middleware\MiddlewareInterface;
use Psr\Http\Middleware\ServerMiddlewareInterface;

/**
 * Class Sequence.
 */
final class Sequence implements DelegateInterface
{
    /**
     * @var DelegateInterface[]
     */
    private $queue;

    /**
     * @var Iterator
     */
    private $iterator;

    /**
     * Constructor.
     *
     * @param Stack $stack
     * @param DelegateInterface $final
     */
    public function __construct(Stack $stack, DelegateInterface $final)
    {
        $this->iterator = $stack->getIterator();
        $this->iterator->rewind();

        $this->queue = array_pad([], $stack->count() - 1, $this);
        array_unshift($this->queue, $final);
    }

    /**
     * {@inheritdoc}
     */
    public function next(RequestInterface $request): ResponseInterface
    {
        $middleware = $this->getNextMiddleware();
        $frame = $this->getNextFrame();

        return $middleware->process($request, $frame);
    }

    /**
     * @return MiddlewareInterface|ClientMiddlewareInterface|ServerMiddlewareInterface
     */
    private function getNextMiddleware(): MiddlewareInterface
    {
        $middleware = $this->iterator->current();
        $this->iterator->next();

        return $middleware;
    }

    /**
     * @return DelegateInterface
     */
    private function getNextFrame(): DelegateInterface
    {
        return array_pop($this->queue);
    }
}
