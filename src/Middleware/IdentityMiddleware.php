<?php

declare(strict_types=1);

namespace App\Middleware;

use Mezzio\Session\RetrieveSession;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Views\Twig;

final class IdentityMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $session = RetrieveSession::fromRequest($request);
        $twig = Twig::fromRequest($request);

        $identity = $session->get('identity');

        $twig['identity'] = $identity;

        return $handler->handle($request->withAttribute('identity', $identity));
    }
}
