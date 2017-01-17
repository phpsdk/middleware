<?php

namespace PhpSDK\Http\Middleware;

use Psr\Http\Middleware\MiddlewareInterface;
use Psr\Http\Middleware\StackInterface;

/**
 * Interface StackBuilder.
 */
interface StackBuilder
{
    /**
     * @return StackInterface
     */
    public function getStack(): StackInterface;

    /**
     * @param MiddlewareInterface $middleware
     * @param MiddlewareInterface[] $more
     *
     * @return StackBuilder
     */
    public function add(MiddlewareInterface $middleware, MiddlewareInterface ...$more): StackBuilder;

    /**
     * @param MiddlewareInterface $middleware
     * @param MiddlewareInterface[] $more
     *
     * @return StackBuilder
     */
    public function remove(MiddlewareInterface $middleware, MiddlewareInterface ...$more): StackBuilder;
}