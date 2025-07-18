<?php

declare(strict_types=1);

namespace App\Middleware;

use Fig\Http\Message\StatusCodeInterface;
use Mezzio\Session\RetrieveSession;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Headers;
use Slim\Psr7\Response;
use Slim\Views\Twig;
use TheNetworg\OAuth2\Client\Provider\Azure;
use TheNetworg\OAuth2\Client\Token\AccessToken;

final readonly class IdentityMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Azure $provider,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = RetrieveSession::fromRequest($request);
        $twig = Twig::fromRequest($request);
        $twig['authorize_uri'] = $this->provider->getAuthorizationUrl();

        if (null === $token = $session->get('token')) {
            return $handler->handle($request);
        }

        $accessToken = unserialize($token);

        if (false === $accessToken instanceof AccessToken) {
            $session->clear();

            return new Response(StatusCodeInterface::STATUS_TEMPORARY_REDIRECT, new Headers(['location' => '/']));
        }

        $resourceOwner = $this->provider->getResourceOwner($accessToken);
        $identity = $resourceOwner->toArray();
        $identity['id'] = $resourceOwner->getId();

        $twig['identity'] = $identity;

        return $handler->handle($request->withAttribute('identity', $identity));
    }
}
