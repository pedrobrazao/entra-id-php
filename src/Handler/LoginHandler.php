<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Session\RetrieveSession;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;
use TheNetworg\OAuth2\Client\Token\AccessToken;

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

        /** @var AccessToken $token */
        $token = $this->provider->getAccessToken('authorization_code', [
            'scope' => $this->provider->scope,
            'code' => $query['code'],
        ]);

        $_SESSION = RetrieveSession::fromRequest($request);
        $_SESSION->clear();
        $_SESSION->set('token', serialize($token));

        return $this->redirectToRoute($request, MeHandler::NAME);
    }
}
