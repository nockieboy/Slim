<?php
/**
 * Slim Framework (https://slimframework.com)
 *
 * @link      https://github.com/slimphp/Slim
 * @copyright Copyright (c) 2011-2018 Josh Lockhart
 * @license   https://github.com/slimphp/Slim/blob/4.x/LICENSE.md (MIT License)
 */
namespace Slim\Tests\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Slim\Middleware\ContentLengthMiddleware;
use Slim\MiddlewareDispatcher;
use Slim\Tests\TestCase;

class ContentLengthMiddlewareTest extends TestCase
{
    public function testAddsContentLength()
    {
        $request = $this->createServerRequest('/');
        $responseFactory = $this->getResponseFactory();

        $mw = function ($request, $handler) use ($responseFactory) {
            $response = $responseFactory->createResponse();
            $response->getBody()->write('Body');
            return $response;
        };
        $mw2 = new ContentLengthMiddleware();

        $middlewareDispatcher = new MiddlewareDispatcher($this->createMock(RequestHandlerInterface::class));
        $middlewareDispatcher->addCallable($mw);
        $middlewareDispatcher->addMiddleware($mw2);
        $response = $middlewareDispatcher->handle($request);

        $this->assertEquals(4, $response->getHeaderLine('Content-Length'));
    }
}
