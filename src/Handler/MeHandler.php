<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\UserService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

final readonly class MeHandler implements RequestHandlerInterface
{
    use HandlerTrait;

    public const NAME = 'me';
    private const TEMPLATE = 'me.html.twig';

    public function __construct(
        private UserService $userService,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $identity = $request->getAttribute('identity');

        if (false === isset($identity['id'])) {
            return $this->redirectToRoute($request, LogoutHandler::NAME);
        }

        if (null === $user = $this->userService->getUserById($identity['id'])) {
            throw new \InvalidArgumentException('User not found.');
        }

        return Twig::fromRequest($request)->render(new Response(), self::TEMPLATE, [
            'user' => $user,
        ]);
    }
}
