<?php

namespace PhpSDK\Http\Middleware;

use Psr\Http\Middleware\DelegateInterface;
use Psr\Http\Middleware\MiddlewareInterface;
use Psr\Http\Middleware\StackInterface;
use SplObjectStorage;

/**
 * Class DefaultStackBuilder.
 */
final class DefaultStackBuilder implements StackBuilder
{
    /**
     * @var SplObjectStorage
     */
    private $storage;

    /**
     * @var DelegateInterface
     */
    private $done;

    /**
     * Constructor.
     *
     * @param DelegateInterface $done
     */
    public function __construct(DelegateInterface $done)
    {
        $this->storage = new SplObjectStorage();
        $this->done = $done;
    }

    /**
     * {@inheritdoc}
     */
    public function getStack(): StackInterface
    {
        return new Stack($this->done, ...$this->storage);
    }

    /**
     * {@inheritdoc}
     */
    public function add(MiddlewareInterface $middleware, MiddlewareInterface ...$more): StackBuilder
    {
        $this->storage->attach($middleware);

        foreach ($more as $middleware) {
            $this->storage->attach($middleware);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove(MiddlewareInterface $middleware, MiddlewareInterface ...$more): StackBuilder
    {
        $this->storage->detach($middleware);

        foreach ($more as $middleware) {
            $this->storage->detach($middleware);
        }

        return $this;
    }
}