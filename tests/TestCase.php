<?php

namespace PhpSDK\Http\Middleware;

use PHPUnit_Framework_MockObject_MockObject as Mock;
use PHPUnit_Framework_TestCase as BaseTestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class TestCase.
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @return Mock|ResponseInterface
     */
    final protected function createResponseMock(): ResponseInterface
    {
        return $this->createMock(ResponseInterface::class);
    }

    /**
     * @return Mock|RequestInterface
     */
    final protected function createRequestMock(): RequestInterface
    {
        return $this->createMock(RequestInterface::class);
    }

    /**
     * @return Mock|ServerRequestInterface
     */
    final protected function createServerRequestMock(): ServerRequestInterface
    {
        return $this->createMock(ServerRequestInterface::class);
    }
}