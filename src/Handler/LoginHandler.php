<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\IdentityService;
use Mezzio\Session\RetrieveSession;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;

final class LoginHandler implements RequestHandlerInterface
{
    use HandlerTrait;

    public const NAME = 'login';

    public function __construct(
        private Azure $provider,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $query = $request->getQueryParams();

        if (false === isset($query['code']) || false === isset($query['state'])) {
            return $this->redirectToRoute($request, HomeHandler::NAME);
        }

        $identityService = new IdentityService($this->provider, RetrieveSession::fromRequest($request));

        if (null === $identityService->getAccessToken($query['code'])) {
            return $this->redirectToRoute($request, HomeHandler::NAME);
        }

        return $this->redirectToRoute($request, MeHandler::NAME);
    }
}
