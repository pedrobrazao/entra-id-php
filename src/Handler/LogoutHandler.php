<?php

declare(strict_types=1);

namespace App\Handler;

use Mezzio\Session\RetrieveSession;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TheNetworg\OAuth2\Client\Provider\Azure;

final class LogoutHandler implements RequestHandlerInterface
{
    use HandlerTrait;

    public const NAME = 'logout';

    public function __construct(
        private Azure $provider,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $_SESSION = RetrieveSession::fromRequest($request);
        $_SESSION->clear();

        $redirectUri = (string) $request->getUri()->withPath('/');
        $url = $this->provider->getLogoutUrl($redirectUri);

        return $this->redirectToUrl($url);
    }
}
