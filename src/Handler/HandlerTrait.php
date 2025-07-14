<?php

declare(strict_types=1);

namespace App\Handler;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Routing\RouteParser;

trait HandlerTrait
{
    private function redirectToUrl(string $url): ResponseInterface
    {
        return new Response(StatusCodeInterface::STATUS_TEMPORARY_REDIRECT, new Headers([
            'location' => $url,
        ]));
    }

    /**
     * @param string[] $params
     */
    private function redirectToRoute(ServerRequestInterface $request, string $routeName, array $params = []): ResponseInterface
    {
        $url = $this->getRouteParser($request)->urlFor($routeName, $params);

        return $this->redirectToUrl($url);
    }

    private function getRouteParser(ServerRequestInterface $request): RouteParser
    {
        return $request->getAttribute('__routeParser__');
    }
}
