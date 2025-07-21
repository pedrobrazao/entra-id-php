<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Service\IdentityService;
use Mezzio\Session\RetrieveSession;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;
use TheNetworg\OAuth2\Client\Provider\Azure;

final readonly class IdentityMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Azure $provider,
    ) {}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = RetrieveSession::fromRequest($request);
        $identityService = new IdentityService($this->provider, $session);
        $twig = Twig::fromRequest($request);

        $twig['authorize_uri'] = $identityService->getAuthorizationUrl();
        $twig['identity'] = $identityService->getIdentity();

        return $handler->handle($request->withAttribute('identity', $twig['identity']));
    }
}
