<?php

namespace PhpSDK\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Middleware\ClientMiddlewareInterface;
use Psr\Http\Middleware\DelegateInterface;
use SplQueue;

/**
 * Class StubMiddleware.
 */
class StubMiddleware implements ClientMiddlewareInterface
{
    /**
     * @var SplQueue
     */
    private $history;

    /**
     * Constructor.
     *
     * @param SplQueue $history
     */
    public function __construct(SplQueue $history)
    {
        $this->history = $history;
    }

    /**
     * {@inheritdoc}
     */
    public function process(RequestInterface $request, DelegateInterface $frame): ResponseInterface
    {
        $this->history->enqueue($this);

        $response = $frame->next($request);

        $this->history->enqueue($this);

        return $response;
    }
}