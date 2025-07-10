<?php

declare(strict_types=1);

namespace App\Handler\Admin;

use App\Service\UserService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;
use Slim\Views\Twig;

final class UserGetHandler implements RequestHandlerInterface
{
    public const NAME = 'admin/user_get';
    private const TEMPLATE = 'admin/user_get.html.twig';

    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $id = RouteContext::fromRequest($request)->getRoute()->getArgument('id');

        if (null === $id || null === $user = $this->userService->getUserById($id)) {
            throw new \InvalidArgumentException('User not found.');
        }

        return Twig::fromRequest($request)->render(new Response(), self::TEMPLATE, [
            'user' => $user,
        ]);
    }
}
