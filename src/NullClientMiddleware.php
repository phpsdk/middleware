<?php

namespace PhpSDK\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Middleware\ClientMiddlewareInterface;
use Psr\Http\Middleware\DelegateInterface;

/**
 * Class NullClientMiddleware.
 */
class NullClientMiddleware implements ClientMiddlewareInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(RequestInterface $request, DelegateInterface $frame): ResponseInterface
    {
        return $frame->next($request);
    }
}