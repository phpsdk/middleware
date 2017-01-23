<?php

namespace PhpSDK\Http\Middleware;

use Countable;
use Iterator;
use IteratorAggregate;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Middleware\ClientMiddlewareInterface;
use Psr\Http\Middleware\DelegateInterface;
use Psr\Http\Middleware\MiddlewareInterface;
use Psr\Http\Middleware\ServerMiddlewareInterface;
use Psr\Http\Middleware\StackInterface;
use SplObjectStorage;

/**
 * Class Stack.
 */
final class Stack implements StackInterface, IteratorAggregate, Countable
{
    /**
     * @var SplObjectStorage|MiddlewareInterface[]|ClientMiddlewareInterface[]|ServerMiddlewareInterface[]
     */
    private $storage;

    /**
     * @var DelegateInterface
     */
    private $done;

    /**
     * Stack constructor.
     *
     * @param DelegateInterface $done
     * @param MiddlewareInterface[] $middleware
     */
    public function __construct(DelegateInterface $done, MiddlewareInterface ...$middleware)
    {
        $this->storage = new SplObjectStorage();
        $this->done = $done;

        foreach ($middleware as $element) {
            $this->storage->attach($element);
        }
    }

    /**
     * Clones object properties.
     */
    public function __clone()
    {
        $this->storage = clone $this->storage;
    }

    /**
     * Counts number of middleware in the stack.
     */
    public function count(): int
    {
        return $this->storage->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Iterator
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function withMiddleware(MiddlewareInterface $middleware): StackInterface
    {
        if ($this->storage->contains($middleware)) {
            return $this;
        }

        $clone = clone $this;
        $clone->storage->attach($middleware);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withoutMiddleware(MiddlewareInterface $middleware): StackInterface
    {
        if (!$this->storage->contains($middleware)) {
            return $this;
        }

        $clone = clone $this;
        $clone->storage->detach($middleware);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function process(RequestInterface $request): ResponseInterface
    {
        $sequence = new Sequence($this, $this->done);

        return $sequence->process($request);
    }
}
