<?php

namespace PhpSDK\Http\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Middleware\DelegateInterface;

/**
 * Class NullHttpHandler.
 */
final class NullHttpHandler implements DelegateInterface
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * Constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function next(RequestInterface $request): ResponseInterface
    {
        return $this->response;
    }
}