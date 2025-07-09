<?php

declare(strict_types=1);

namespace App\Handler\Admin;

use App\Service\UserService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response;
use Slim\Views\Twig;

final class UserListHandler implements RequestHandlerInterface
{
    public const NAME = 'admin/user_list';
    private const TEMPLATE = 'admin/user_list.html.twig';

    public function __construct(
        private readonly UserService $userService,
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $users = $this->userService->listUsers();
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        return Twig::fromRequest($request)->render(new Response(), self::TEMPLATE, [
            'users' => $users ?? [],
            'error' => $error ?? null,
        ]);
    }
}
