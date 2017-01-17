<?php

namespace PhpSDK\Http\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Middleware\DelegateInterface;
use Psr\Http\Middleware\ServerMiddlewareInterface;

/**
 * Class NullServerMiddleware.
 */
class NullServerMiddleware implements ServerMiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ServerRequestInterface $request, DelegateInterface $frame): ResponseInterface
    {
        return $frame->next($request);
    }
}